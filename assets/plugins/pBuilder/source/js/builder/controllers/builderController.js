angular.module('builder').controller('BuilderController', ['$scope', '$rootScope', '$translate', 'bootstrapper', 'elements', 'settings', 'grid', 'preview', 'themes', 'fonts', 'panels', function($scope, $rootScope, $translate, bootstrapper, elements, settings, grid, preview, themes, fonts, panels) {
    $scope.themes = themes;
    $scope.settings = settings;
    $scope.fonts = fonts;

    $('#view').removeClass('loading');

    $scope.bootstrapper = bootstrapper;
    bootstrapper.start();

    $scope.closePreview = function() {
        preview.hide();
    };

    /**
     * Whether or not passed in attribute is editable
     * on the currently active DOM node.
     *
     * @param  {string} prop
     * @return boolean
     */
    $scope.canEdit = function(prop) {

        if ( ! $scope.selected.node) {
            return true;
        } else {
            return $scope.selected.element && $scope.selected.element.canModify.indexOf(prop) !== -1;
        }
    };

    $scope.openPanel = function(name) {
        $rootScope.activePanel = name;
        $rootScope.flyoutOpen = true;
    };

    /**
     * Reposition select or hover boxes on the screen.
     *
     * @param  {string}  name
     * @param  {mixed}   node
     * @param  {mixed}   el
     *
     * @return void
     */
    $rootScope.repositionBox = function(name, node, el) {

        //hide context boxes depending on user settings
        if (! settings.get('enable'+name.ucFirst()+'Box')) {
            return $scope[name+'Box'].hide();
        }

        if (! node) {
            node = $scope.selected.node;
        }

        if (node && node.nodeName == 'BODY') {
            return $scope[name+'Box'].hide();
        }

        if (! el) {
            el = $scope.selected.element;
        }

        if (! el) return true;

        if (name == 'select') {
            $scope.hoverBox.hide();
        }

        var rect = node.getBoundingClientRect();

        if ( ! rect.width || ! rect.height) {
            $scope[name+'Box'].hide();
        } else {
            $scope[name+'Box'].css({
                top: rect.top,
                left: rect.left - 7 + $scope.frameOffset.left - $scope.elemsContWidth,
                height: rect.height,
                width: rect.width
            }).show();

            $scope[name+'BoxTag'].textContent = $translate.instant(el.name);

            //make sure boxes don't go over the breadcrumbs
            if (rect.top + 39 < 55) {
                $scope[name+'BoxActions'].style.top = 0;
            } else {
                $scope[name+'BoxActions'].style.top = '-27px';
            }
        }
    };

    $rootScope.elementFromPoint = function(x, y) {
        var el = $scope.frameDoc.elementFromPoint(x, y);

        //firefox returns html if body is empty,
        //IE doesn't work at all sometimes.
        if ( ! el || el.nodeName === 'HTML') {
            return $scope.frameBody[0];
        }

        return el;
    };

    /**
     * Set given node as active one in the builder.
     * Wrap calls to this method in $apply to avoid sync problems.
     *
     * @param  {object} node
     * @return void
     */
    $rootScope.selectNode = function(node) {
        if ($scope.rowEditorOpen) { return true; }

        $scope.selecting = true;

        $scope.selected.previous = $scope.selected.node;

        //if we get passed an integer instead of a dom node we'll
        //select a node at that index in the currently stored path
        if (angular.isNumber(node)) {
            node = $scope.selected.path[node].node;
        }

        //if we haven't already stored a reference to passed in node, do it now
        if (node && $scope.selected.node !== node) {
            $scope.selected.node = node;
        }

        //cache some more references about the node for later use
        $scope.selected.element = elements.match($scope.selected.node, 'select', true);
        $scope.selected.parent = $scope.selected.node.parentNode;
        $scope.selected.parentContents = $scope.selected.parent.childNodes;

        //position select box on top of the newly selected node
        $scope.repositionBox('select');

        //whether or not the new node is locked
        $scope.selected.locked = $scope.selected.node.className.indexOf('locked') > -1;
        $scope.selected.isImage = $scope.selected.node.nodeName == 'IMG' &&
        $scope.selected.node.className.indexOf('preview-node') === -1;

        //create an array from all parents of this node
        var parents = $($scope.selected.node).parentsUntil('body').toArray();
        parents.unshift($scope.selected.node);

        //create a path to use for breadcrumbs and css selectors
        $scope.selected.path = $.map(parents.reverse(), function(node) {
            return { node: node, name: elements.match(node).name };
        });

        //whether or not this node is a column
        $scope.selected.isColumn = grid.isColumn($scope.selected.node);

        $scope.frameWindow.focus();

        $rootScope.$broadcast('element.reselected', $scope.selected.node);

        panels.open('inspector');

        setTimeout(function(){
            $scope.selecting = false;
        }, 200);
    };
}]);
