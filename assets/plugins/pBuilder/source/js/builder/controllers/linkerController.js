angular.module('builder').controller('LinkerController', ['$scope', 'project', function($scope, project) {

	$scope.project = project;

	$scope.linker = {};

	$scope.hideLinker = function() {
		$('#linker').addClass('hidden');
	};

	$scope.removeLink = function() {
		if ($scope.selected.node.tagName === 'A') {
			$scope.selected.node.href = '';
			$scope.selected.node.removeAttribute('download');
		}
	};

	$scope.applyUrl = function() {
		if ( ! $scope.linker.url) { return false };

		$scope.selected.node.href = $scope.linker.url;
		$scope.selected.node.removeAttribute('download');
	};

	$scope.applyPage = function() {
		if ( ! $scope.linker.page) { return false };

		$scope.selected.node.href = $scope.linker.page+'.html';
		$scope.selected.node.removeAttribute('download');
	};

	$scope.applyEmail = function() {
		if ( ! $scope.linker.email) { return false };

		$scope.selected.node.href = 'mailto:'+$scope.linker.email;
		$scope.selected.node.removeAttribute('download');
	};

	$scope.applyDownload = function() {
		if ( ! $scope.linker.download) { return false };

		$scope.selected.node.href = $scope.linker.download;
		$scope.selected.node.setAttribute('download', 'download');
	}


	$scope.$on('element.reselected', function() {
		if ($scope.selected.node.tagName === 'A') {
			var href = $scope.selected.node.getAttribute('href');

			if ( ! href) { return false };

			$scope.linker.label = $scope.selected.node.textContent;

			//it's an url
			if (href.indexOf('//') !== -1 || href.indexOf('#') !== -1) {
				$scope.linker.url = href;
				$scope.linker.radio = 'url';
			}

			//it's an email
			else if (href.indexOf('mailto:') !== -1) {
				$scope.linker.email = href;
				$scope.linker.radio = 'email';
			}

			//it's a download
			else if ($scope.selected.node.hasAttribute('download')) {
				$scope.linker.download = href;
				$scope.linker.radio = 'download';
			}

			//it's a page
			else {
				$scope.linker.page = href.replace('.html', '');
				$scope.linker.radio = 'page';
			}
		}
	});

}])