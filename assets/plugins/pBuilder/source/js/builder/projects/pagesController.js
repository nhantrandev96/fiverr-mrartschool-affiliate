angular.module('builder.projects')

.controller('PagesController', ['$scope', '$http', '$translate', 'project', 'localStorage', function($scope, $http, $translate, project, localStorage) {
	$scope.project = project;

    $scope.loading = false;

    $scope.emptyProject = function() {
        alertify.confirm($translate.instant('emptyProjectConfirmation'), function (e) {
            if (e) {
                $scope.loading = true;
                project.clear();
                $scope.loading = false;
            }
        });
    };

    $scope.createNewPage = function() {
        $scope.loading = true;

        var name = 'Page'+(project.active.pages.length+1);

        //create a new page object
        project.active.pages.push({
            name: name
        });

        //save new page to local storage
        project.save('page');
        project.changePage(name);
        $scope.loading = false;
    };

    //Delete currently active page
    $scope.deletePage = function() {

        if (project.active.pages.length < 2) {
            return alertify.log('You need to have at least 1 page.', 'error', 3000);
        }

        alertify.confirm($translate.instant('pageDeleteConfirmation'), function (e) {
            if (e) {
                $scope.loading = true;
                project.removePage(project.activePage.name);
                $scope.loading = false;
            }
        });
    };

    //Save currently active page
    $scope.savePage = function() {
        $scope.loading = true;
        
        project.save('all');
        $scope.loading = false;
        localStorage.set('activePage', project.activePage.name);
        alertify.log(project.activePage.name+' saved successfully.', 'success', 2000);
    };

    //Copy currently active page
    $scope.copyPage = function() {
        $scope.loading = true;

        //prepare a page object copy
        var copy = $.extend({}, $scope.project.activePage);
        copy.name = copy.name + ' Copy';
        delete copy.id; delete copy.$$hashKey;

        //make sure we get a unique name for page copy
        for (var i = 0; i < $scope.project.active.pages.length; i++) {
            if ($scope.project.active.pages[i].name === copy.name) {
                copy.name = copy.name + ' Copy';
            }
        }

        //push to pages array
        $scope.project.active.pages.push(copy);

        //save to db
        project.save('page');
        $scope.loading = false;
        project.changePage(copy.name);
    };
}]);