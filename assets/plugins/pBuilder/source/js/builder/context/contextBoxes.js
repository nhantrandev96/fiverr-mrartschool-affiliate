'use strict';

angular.module('builder')

.directive('blContextBoxActions', ['dom', function(dom) {
    return {
        restrict: 'A',
        link: function($scope, el) {

            el.on('click', '.icon', function(e) {
                var action = e.currentTarget.dataset.action;

                if (action == 'delete') {
                    dom.delete($scope.selected.node);
                } else if (action == 'edit') {
                    if ($scope.selected.element.onEdit) {
                        $scope.selected.element.onEdit($scope);
                    } else if ($scope.canEdit('text')) {
                        $scope.$broadcast('builder.contextBox.editBtn.click');
                    }
                }
            });
        }
    }
}])

.directive('blIframeNodesSelectable', ['$timeout', function($timeout) {
    return {
        restrict: 'A',
        link: function($scope) {
            $scope.$on('builder.dom.loaded', function() {
                $scope.frameBody.off('click').on('click', function(e) {
                    e.preventDefault();

                    //hide context menu
                    $scope.contextMenu.hide();

                    $scope.frameWindow.focus();

                    if ($scope.resizing || $scope.selected.node == e.target) { return true; };

                    var node = e.target;

                    if (node.hasAttribute('contenteditable') || node.parentNode.hasAttribute('contenteditable')) {
                        return true;
                    }

                    var editable = $scope.frameBody.find('[contenteditable]');

                    for (var i = editable.length - 1; i >= 0; i--) {
                        editable[i].removeAttribute('contenteditable');
                        editable[i].blur();
                    };

                    //hide wysiwyg toolbar when clicked outside it
                    if ( ! $scope.textToolbar.hasClass('hidden')) {
                        $scope.textToolbar.addClass('hidden');
                        $scope.$emit('builder.html.changed');
                    }

                    //hide linker
                    $scope.linker.addClass('hidden');

                    //hide colorpicker when clicked outside it and if it exists
                    if ($scope.colorPickerCont) {
                        $scope.colorPickerCont.addClass('hidden');
                    }

                    if (node.nodeName != 'HTML') {
                        $scope.$apply(function() {
                            $scope.selectNode(node);
                        });
                    }
                });
            });

            //reposition select box when iframe is scrolled
            $scope.$on('builder.dom.loaded', function() {
                $($scope.frameDoc).on('scroll', function() {
                    $scope.repositionBox('select');
                });
            });
        }
    }
}])

.directive('blHoverBox', ['elements', 'dom', function(elements, dom) {
	return {
		restrict: 'A',
		replace: true,
		template: '<div id="hover-box"><div id="hover-box-actions"><span class="element-tag">#</span></div></div>',
		link: function($scope, el) {

            $scope.$on('builder.dom.loaded', function(e) {
                $scope.frameBody.off('mousemove').on('mousemove', function(e) {
                    if ( ! $scope.dragging) {
                        $scope.hover.previous = $scope.hover.node;

                        var node = $scope.elementFromPoint(e.pageX, e.pageY - $scope.frameBody.scrollTop());
                        
                        //hide hover box and bail if we're hovering over a selected node
                        if ($scope.selected.node && $scope.selected.node == node) {
                            return $scope.hoverBox.hide();
                        }

                        //make sure we don't select resize handles
                        if (node.className.indexOf('ui-resizable-handle') == -1) {
                            $scope.hover.node = node;

                            $scope.hover.element = elements.match($scope.hover.node, 'hover', true);
                            
                            //only reposition hover box during drag on webkit browsers
                            //as it will cause fairly significant lag on IE and Firefox
                            if ( ! $scope.dragging || $scope.isWebkit) {
                                $scope.repositionBox('hover', $scope.hover.node, $scope.hover.element);
                            }                      
                        }
                    }
                });
            });
        
      		
		}
	}
}]);