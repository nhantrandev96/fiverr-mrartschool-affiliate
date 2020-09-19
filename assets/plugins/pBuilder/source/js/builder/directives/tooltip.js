'use strict';

angular.module('builder.directives')

.directive('blTooltip', ['$translate', function($translate) {
    return {
        restrict: 'A',
        link: function($scope, el, attrs) {
            $translate(attrs.blTooltip).then(function (translation) {
                el.tooltip({
                    placement: attrs.placement || 'bottom',
                    delay: 50,
                    title: translation
                })
            });

        }
    }
}]);