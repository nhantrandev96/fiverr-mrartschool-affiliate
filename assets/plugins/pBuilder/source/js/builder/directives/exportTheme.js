angular.module('builder.directives').directive('blExportTheme', ['themes', function(themes) {
    return {
        restrict: 'A',
        link: function($scope, el) {

            el.on('click', function(e) {

                //clear previous error message
                $scope.$apply(function() {
                    $scope.export.errorMessage = '';
                });

                //if no theme selected show an error message
                if ( ! themes.active) {
                    $scope.$apply(function() {
                        return $scope.export.errorMessage = 'Please select a theme.';
                    });
                }

                //if this is a first export create an iframe
                if ( ! $scope.downloadIframe) {
                    $scope.downloadIframe = $('<iframe style="display:none"></iframe>').appendTo(el);
                }

                //set iframe src to the server side download link
                $scope.downloadIframe.attr('src', $scope.baseUrl+'/export/theme/'+themes.active.name);
            });
        }
    };
}]);