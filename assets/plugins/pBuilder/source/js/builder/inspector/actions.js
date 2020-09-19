angular.module('builder.inspector')

.directive('blToggleElementLock', function() {
	return {
		restrict: 'A',
		link: function($scope, el, attrs, controller) {
			el.on('click', function(e) {
		
				$scope.$apply(function() {

					//unlock
					if ($scope.selected.locked) {
						$($scope.selected.node).removeClass('locked');
						$scope.selected.locked = false;

					//lock
					} else {
						$($scope.selected.node).addClass('locked');
						$scope.selected.locked = true;
					}
				});

				return false;
			});
		}
	};
});

