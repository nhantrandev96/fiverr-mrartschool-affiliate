angular.module('builder.directives').directive('blColorPicker', ['$parse', '$rootScope', 'inspector', function($parse, $rootScope, inspector) {

    return {
        restrict: 'A',
        link: function($scope, el) {

            //create an input element to instantiate color picker on
            var input = $('<input id="color-picker"/>').prependTo(el);

            //initiate color picker with some predefined colors to choose from
            input.spectrum({
                flat: true,
                palette: colorsForPicker,
                showAlpha: true,
                showPalette: true,
                showInput: true
            });

            //cache values needed for color picker repositioning
            var container  = $('#inspector .sp-container'),
                triggers   = $('.color-picker-trigger'),
                contHeight = container.height(),
                arrow      = $('#color-picker-arrow'),
                bottomEdge = $('#viewport').height();

            $rootScope.colorPickerCont = container.add(arrow).addClass('hidden');

            container.find('.sp-choose').on('click', function(e) {
                $rootScope.colorPickerCont.addClass('hidden');
                return false;
            });

            triggers.on('click', function(e) {
                var top   = $(e.target).offset().top + (e.currentTarget.offsetHeight/2 - 15),
                    model = e.currentTarget.dataset.controls,
                    scope = angular.element(e.currentTarget).scope();

                if ( ! $scope.colorProperty || $scope.colorProperty == model) {
                    $rootScope.colorPickerCont.toggleClass('hidden');
                }

                //hide image/gradient panel
                $('#background-flyout-panel').addClass('hidden');
                $('#background-arrow').hide();

                input.off('move.spectrum').on('move.spectrum', function(e, color) {

                    var rgb = color.toRgb();

                    //if transparency is 0 or 1 convert to hex
                    //format otherwise convert to rgba format
                    if (rgb.a === 0 || rgb.a === 1) {
                        color = color.toHexString();
                    } else {
                        color = color.toRgbString();
                    }

                    //write current color on inspector so we have real time
                    //reflection on element in the DOM
                    scope.$apply(function() {
                        $parse(model).assign(scope, color);
                    });
                });

                var tempTop = top - contHeight/2 + 10;

                //position picker 15px above bottom edge if not enough space normally
                if (bottomEdge < (tempTop + contHeight)) {
                    tempTop = bottomEdge - contHeight - 15;
                }

                //position picker 15px below top edge if not enough space normally
                if (tempTop < 0) {
                    tempTop = 15;
                }

                container.css('top', tempTop);

                arrow.css({
                    display: 'block',
                    left: 309,
                    top:  top
                });

                input.spectrum('set', $parse(model)(scope));

                //set trigger as previous and grab color propperty (background, border-color etc)
                //from targets data attribute so we know what we should apply colors to
                $scope.colorProperty = e.currentTarget.dataset.controls;
                inspector.styles.color.property = $scope.colorProperty;
            });

            //create the command to undo color change on dragstart
            input.on("dragstart.spectrum", function(e, color) {
                inspector.sliding = true;
                $scope.$broadcast($scope.colorProperty+'.slidestart', 'color');
            });

            //add the command to undo manager on dragstop
            input.on("dragstop.spectrum", function() {
                inspector.sliding = false;
                $scope.$broadcast($scope.colorProperty+'.slidestop', 'color');
            });
        }
    }

}]);