angular.module('builder.inspector')

.controller('MediaManagerController', ['$scope', '$upload', '$http', '$translate', 'inspector', function($scope, $upload, $http, $translate, inspector) {

		$scope.modal = $('#images-modal');

		$scope.sorting = { prop: 'created_at', reverse: false };

		//upload files to filesystem
        $scope.onFileSelect = function($files, replace) {
		    for (var i = 0; i < $files.length; i++) {
		      	$scope.upload = $upload.upload({
		        	url: 'backend/upload-image.php',
		        	file: $files[i]
		      	}).success(function(data) {
                    if (replace === 'src') {
                        $scope.selected.node.src = data;
                    }

                    if (replace === 'bg') {
                        inspector.applyCss('background-image', 'url("'+data+'")', $scope.selected.getStyle('background-image'));
                    }
		      	}).error(function() {
		      	    alertify.error($translate.instant('imageUploadFail'), 3000);
		      	})
		    }
		};	

		$scope.useImage = function() {
			if ($('#images-modal').data('type') == 'background') {
				$scope.setAsBackground();
			} else {
				$scope.setAsSource();
			}

			$scope.modal.modal('hide');
		};

		$scope.activeTab = 'my-images';
}])