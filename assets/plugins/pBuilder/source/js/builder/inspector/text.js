angular.module('builder.inspector')

.directive('blToggleTextDecoration', function() {
    return {
        restrict: 'A',  
        link: function($scope, el, attrs) {     	
            el.on('click', function(e) {
            	var deco = attrs.blToggleTextDecoration,
            		scopeDeco = $scope.inspector.styles.text.textDecoration.trim();

            	$scope.$apply(function() {	
            		//element has no text decoration currently so we'll just apply it now
            		if ( ! scopeDeco || scopeDeco.match(/^.*(none|initial).*$/)) {
            			$scope.inspector.styles.text.textDecoration = deco;

            		//element has given text decoration already so we'll remove it
            		} else if (deco == scopeDeco) {
            			$scope.inspector.styles.text.textDecoration = 'none';

            		//element has given text decoration as well as other decorations
            		//(underline overline) so we'll just remove given one and leave others intact
            		} else if (scopeDeco.match(deco)) {
            			$scope.inspector.styles.text.textDecoration = scopeDeco.replace(deco, '').trim();

            		//element has other text decorations but not this one so we'll append it to existing ones
            		} else {
            			$scope.inspector.styles.text.textDecoration += ' ' + deco;
            		}        		
            	});       	
            });
        }
    }
})

.directive('blToggleTextStyle', function() {
    return {
        restrict: 'A',  
        link: function($scope, el, attrs) {     	
            el.on('click', function(e) {
            	var split = attrs.blToggleTextStyle.split('|');

            	$scope.$apply(function() {
            		if (el.hasClass('active')) {
	            		el.removeClass('active');
	            		$scope.inspector.styles.text[split[0]] = 'initial';
	            	} else {
	            		$scope.inspector.styles.text[split[0]] = split[1];
	            		el.addClass('active');

	            		if (split[1] != 'italic') {
	            			el.siblings().removeClass('active');
	            		}
	            	}
            	});
            });
        }
    }
})

.controller('TextController', ['$scope', 'inspector', 'undoManager', 'fonts', 'textStyles', function($scope, inspector, undoManager, fonts, textStyles) {
	
	$scope.inspector = inspector;

	//text styles values
	$scope.textStyles = textStyles;
	$scope.fontSelect = $('#el-font-family');
	$scope.fonts      = fonts;

    fonts.getAll();

	$scope.$watchCollection('inspector.styles.text', function(newValue, oldValue) {
		if (! $scope.selecting && ! $scope.dragging) {
			//loop trough both objects and only act if there's a difference in their values
			for (var prop in newValue) {
				if (newValue[prop] && oldValue[prop] && newValue[prop].removeQoutes() !== oldValue[prop].removeQoutes()) {
					return inspector.applyCss(prop.toDashedCase(), newValue[prop], oldValue[prop]);
				}
			}
		}
	});

	//grab current text/font styles for active element
	$scope.$on('element.reselected', function(e) {
		inspector.styles.text = {
			color: $scope.selected.getStyle('color'),
			fontSize: $scope.selected.getStyle('font-size'),
			textAlign: $scope.selected.getStyle('text-align'),
			fontStyle: $scope.selected.getStyle('font-style'),
			fontFamily: $scope.selected.getStyle('font-family'),
			lineHeight: $scope.selected.getStyle('line-height'),
			fontWeight: $scope.selected.getStyle('font-weight'),
			textDecoration: $scope.selected.getStyle('text-decoration')
		}
	});

	//make only one text color undo command on slider stop event
	//so we don't flood it with thousands of similar color commands
	$scope.$on('text.slidestart', function(e) {
		$scope.command = {
			property: 'color',
			node: $scope.selected.node,
			path: $scope.selected.path,
			oldStyles: inspector.styles.text.color,
		}
	});
	$scope.$on('text.slidestop', function(e) {
		$scope.command.newStyles = inspector.styles.text.color;
		undoManager.add('revertStyles', $scope.command);
	});
}]);