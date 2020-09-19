angular.module('builder.directives').directive('blThemesFilter', function() {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function($scope, element, attr, ngModel) {

            function fromUser(val) {
                if (val == 'bootswatch') {
                    return { source: 'bootswatch' };
                } else if (val == 'yours') {
                    return { userId: $scope.user.id };
                } else if (val == 'public') {
                    return { type: 'public' };
                }
            }

            ngModel.$parsers.push(fromUser);
        }
    };
});