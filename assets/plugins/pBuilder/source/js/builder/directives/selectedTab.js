angular.module('builder.directives').directive('selectedTab', function() {
    return {
        restrict: 'E',
        replace: true,
        template: '<div class="selected-tab"><span class="top"></span><span class="bottom"></span></div>',
        link: function($scope, el) {
            var offsetTop = 121;

            if ($(document).height() <= 650) {
                offsetTop = 21;
            }

            $('.main-nav').on('click', '.nav-item', function(e) {
                if ( ! e.currentTarget.hasAttribute('not-selectable')) {
                    el.css('transform', 'translateY('+(e.currentTarget.getBoundingClientRect().top-offsetTop)+'px)');
                }
            });
        }
    }
});