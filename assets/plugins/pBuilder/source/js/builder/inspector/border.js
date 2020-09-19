angular.module('builder.inspector')

.directive('blBorderColorPreview', function() {
    return {
        restrict: 'A',  
        link: function($scope, el, attrs) {     	
           	$scope.$watchCollection('inspector.styles.border.color', function(newV) {
           		if ( ! newV) return true;

           		el.css({
           			borderTopColor: newV.top,
           			borderRightColor: newV.right,
           			borderBottomColor: newV.bottom,
           			borderLeftColor: newV.left,
           		});
           	});
        }
    }
})

.controller('BorderController', ['$scope', 'inspector', 'undoManager', function($scope, inspector, undoManager) {
	
	$scope.inspector = inspector;

	$scope.$watchCollection('inspector.styles.border.radius', function(newV, oldV) {
		if (! $scope.selecting && ! $scope.dragging) {
			return inspector.applyCss('border-radius', newV, oldV);
		}
	});
	$scope.$watchCollection('inspector.styles.border.width', function(newV, oldV) {
		if (! $scope.selecting && ! $scope.dragging) {
			return inspector.applyCss('border-width', newV, oldV);
		}
	});
	$scope.$watch('inspector.styles.border.style', function(newV, oldV) {
		if (! $scope.selecting && ! $scope.dragging) {
			return inspector.applyCss('border-style', newV, oldV);
		}
	});
	$scope.$watchCollection('inspector.styles.border.color', function(newV, oldV) {
		if (! $scope.selecting && ! $scope.dragging) {
			return inspector.applyCss('border-color', newV, oldV);
		}
	});

	//grab current css border styles for selected element so we can have a preview in inspector
	$scope.$on('element.reselected', function(e) {		
		var style = $scope.selected.getStyle('border-style');

		inspector.styles.border.style = style.normalizeCss();

		$scope.borderStyle = style.split(' ')[0];
		$scope.borderRadius = '';

		inspector.styles.border.color = {
			top: $scope.selected.getStyle('border-top-color'),
			right: $scope.selected.getStyle('border-right-color'),
			left: $scope.selected.getStyle('border-left-color'),
			bottom: $scope.selected.getStyle('border-bottom-color'),	
		}

		inspector.styles.border.width = {
			top: $scope.selected.getStyle('border-top-width'),
			right: $scope.selected.getStyle('border-right-width'),
			left: $scope.selected.getStyle('border-left-width'),
			bottom: $scope.selected.getStyle('border-bottom-width'),	
		}

		inspector.styles.border.radius = {
			topRight: $scope.selected.getStyle('border-top-right-radius'),
			topLeft: $scope.selected.getStyle('border-top-left-radius'),
			bottomRight: $scope.selected.getStyle('border-bottom-right-radius'),
			bottomLeft: $scope.selected.getStyle('border-bottom-left-radius'),	
		}
	});

	//watch for border color changes on inspector as there's only one color picker
	//so the color change can't be logged on scope to individual properties
	$scope.$watch('inspector.styles.color.value', function(newColor, oldColor) {
		if (inspector.styles.color.property == 'border') {
			inspector.changeStyle('border', 'color', inspector.checkboxes.border, newColor);
		}
	});

	//watch for border style changes on scope and reflect them to inspector
	$scope.$watch('borderWidth', function(newWidth) {
		inspector.changeStyle('border', 'width', inspector.checkboxes.borderWidth, newWidth, 'px');
	});
	$scope.$watch('borderRadius', function(newRadius) {
		inspector.changeStyle('border', 'radius', inspector.checkboxes.borderRadius, newRadius, 'px');
	});
	$scope.$watch('borderStyle', function(newStyle) {
		inspector.changeStyle('border', 'style', inspector.checkboxes.borderWidth, newStyle);
	});
	$scope.$watch('border.widthAll', function(w) {
		inspector.styles.border.width = {top: w, left: w, right: w, bottom: w};
	});
	$scope.$watch('radiusAll', function(r) {
		inspector.styles.border.radius = {topRight: r, topLeft: r,bottomRight: r, bottomLeft: r};
	});

	//store old border styles on range slider start for undo command
	$scope.$on('border.slidestart', function(e, prop) {
		if (prop == 'color') prop = 'border-color';

		$scope.command = {
			property: prop.toDashedCase(),
			node: $scope.selected.node,
			path: $scope.selected.path,
			oldStyles: $scope.prepareUndoValue(prop)
		}
	});

	//store new border styles on range slider stop and add the undo command
	$scope.$on('border.slidestop', function(e, prop) {
		$scope.command.newStyles = $scope.prepareUndoValue(prop)
		undoManager.add('revertStyles', $scope.command);
	});

	$scope.prepareUndoValue = function(prop) {
		var attr = prop.toDashedCase().replace('border-', '');

		//make sure we copy the object and not get it by reference
		if (angular.isObject(inspector.styles.border[attr])) {
			return $.extend({}, inspector.styles.border[attr]);
		} else {
			return inspector.styles.border[attr];
		}
	}
}]);