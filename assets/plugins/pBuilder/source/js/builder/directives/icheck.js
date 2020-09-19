'use strict';

angular.module('builder.directives')

.directive('icheck', ['$timeout', '$translate', function($timeout, $translate) {
    return {
        require: 'ngModel',
        link: function($scope, el, attrs, ctrl) {
            $timeout(function() {

                //init
                el.iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue'
                });

                //set initial state
                if (ctrl.$modelValue) {
                    el.iCheck('check');
                } else {
                    el.iCheck('uncheck');
                }

                //bind angular model to checkbox
                el.on('ifChecked', function() {
                    ctrl.$setViewValue(true);
                }).on('ifUnchecked', function() {
                    ctrl.$setViewValue(false);
                })
            })
        }
    };
}]);