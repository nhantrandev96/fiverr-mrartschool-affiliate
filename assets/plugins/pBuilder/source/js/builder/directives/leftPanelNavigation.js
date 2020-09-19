angular.module('builder.directives').directive('leftPanelNavigation', ['panels', function(panels) {
    return {
        restrict: 'A',
        link: function($scope, el) {
            el.on('click', '.nav-item', function(e) {
                if ( ! e.currentTarget.hasAttribute('not-selectable')) {
                    $scope.$apply(function() {
                        panels.active = e.currentTarget.dataset.name;
                    });
                }
            });
        }
    }
}]);