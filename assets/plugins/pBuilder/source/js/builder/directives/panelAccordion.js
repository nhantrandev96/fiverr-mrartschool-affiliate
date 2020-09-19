angular.module('builder.directives').directive('blPanelsAccordion', function() {
    return {
        restrict: 'A',
        link: function($scope, el) {
            el.on('click', '.accordion-heading', function(e) {
                var item = $(e.target).closest('.accordion-item');

                if (item.hasClass('open')) {
                    el.find('.accordion-item').removeClass('open');
                } else {
                    el.find('.accordion-item').removeClass('open');
                    $scope.$apply(function() {
                        item.addClass('open');
                    });
                }
            });
        }
    }
});