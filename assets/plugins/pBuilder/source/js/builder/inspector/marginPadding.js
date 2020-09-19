angular.module('builder.inspector')

.controller('MarginPaddingController', ['$scope', 'inspector', 'undoManager', function($scope, inspector, undoManager) {
	
	$scope.inspector = inspector;

	//grab current css padding and margin styles for selected
	//element so we can have a preview in inspector
	$scope.$on('element.reselected', function(e, node) {	
		inspector.styles.padding = {
			top: $scope.selected.getStyle('padding-top'),
			left: $scope.selected.getStyle('padding-left'),
			right: $scope.selected.getStyle('padding-right'),			
			bottom: $scope.selected.getStyle('padding-bottom')
		}

		inspector.styles.margin = { 
			top: $scope.selected.getStyle('margin-top'),
			left: $scope.selected.getStyle('margin-left'),
			right: $scope.selected.getStyle('margin-right'),			
			bottom: $scope.selected.getStyle('margin-bottom')
		}

		$scope.paddingAll = null;
		$scope.marginAll  = null;	
	});

	//store old border styles on range slider start for undo command
	$scope.$on('padding.slidestart', function(e, prop) {
		$scope.command = {
			property: 'padding',
			node: $scope.selected.node,
			path: $scope.selected.path,
			oldStyles: $.extend({}, inspector.styles.padding),
		}
	});
	$scope.$on('margin.slidestart', function(e, prop) {
		$scope.command = {
			property: 'margin',
			node: $scope.selected.node,
			path: $scope.selected.path,
			oldStyles: $.extend({}, inspector.styles.margin),
		}
	});
	
	//store new border styles on range slider stop and add the undo command
	$scope.$on('padding.slidestop', function(e, prop) {
		$scope.command.newStyles = $.extend({}, inspector.styles.padding)
		undoManager.add('revertStyles', $scope.command);
	});
	$scope.$on('margin.slidestop', function(e, prop) {
		$scope.command.newStyles = $.extend({}, inspector.styles.margin)
		undoManager.add('revertStyles', $scope.command);
	});
}]);