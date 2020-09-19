angular.module('builder.inspector')

.controller('ShadowsController', ['$scope', 'inspector', function($scope, inspector) {

	$scope.inspector = inspector;
	
	$scope.props = {
		type: 'boxShadow',
		inset: false,
		angle: 0,
		distance: 5,
		blur: 10,
		color: 'rgba(50, 50, 50, 0.75)',
		spread: 0
	};

	$scope.$watchCollection('props', function(newProps, oldProps) {
		if (! $scope.selecting && ! $scope.dragging) {	
			inspector.applyCss(newProps.type, $scope.compileCssString(newProps), $scope.compileCssString(oldProps));
		}
	});

	$scope.compileCssString = function(props) {
		var blur   = Math.round(props.blur),
			spread = Math.round(props.spread),		
			angle  = parseInt(props.angle)*((Math.PI)/180),
			x      = Math.round(props.distance * Math.cos(angle)),
			y      = Math.round(props.distance * Math.sin(angle)),
			inset  = props.inset && props.type == 'boxShadow' ? 'inset ' : '',
			css    = inset+x+'px '+y+'px '+blur+'px ';

			//text shadows have no spread property
			if (props.type == 'boxShadow') {
				css += spread+'px ';
			}

		return css+props.color;
	};

	$scope.stringToProps = function(string) {
		if ( ! string || string == 'none') {
			$scope.props.angle = 0;
			$scope.props.distance = 5;
			$scope.props.blur = 10;
			$scope.props.color = 'rgba(50, 50, 50, 0.75)';
			$scope.props.spread = 0;

			return true;
		}

		var array = string.replace(/, /g, ',').split(' ');
		
		//jquery string
		if (string.charAt(0) == '#' || string.charAt('r')) {
			array.push(array.shift());
		}

		//text shadow
		if (array.length == 4) {
			$scope.props.blur = array[2].replace('px', '');
			$scope.props.color = array[3];
			$scope.props.distance = parseInt(array[0].replace('px', '')) + 3;
		} 

		//box shadow
		else if (array.length == 5 || array.length == 6) {
			$scope.props.blur = array[2].replace('px', '');
			$scope.props.spread = array[3].replace('px', '');
			$scope.props.color = array[4].replace('px', '');
			$scope.props.distance = parseInt(array[0].replace('px', '')) + 3;
		}
	};

	$scope.$on('element.reselected', function(e, node) {

		if ($scope.props.type == 'boxShadow') {
			var shadow = node.style.boxShadow
		} else {
			var shadow = node.style.textShadow
		}

		$scope.stringToProps(shadow);
	});
}])

.directive('blKnob', function() {
	return {
		restrict: 'A',
		link: function($scope, el) {
			el.knob({
		    	min: 0,
		    	max: 360,
		    	cursor: 40,
		    	thickness: '.4',
		    	width: 60,
		    	height: 60,
		    	angleOffset: 90,
		    	fgColor: '#00b588',
		    	bgColor: '#263845',
		    	change: function(v) {
		    		$scope.inspector.sliding = true;
		    		$scope.$apply(function() {
			   			$scope.props.angle = v;
		    		});
		    	},
		    	release: function(v) {	    		
		    		$scope.inspector.sliding = false;	
		    		$scope.$broadcast('angle.slidestop', v);
		    	}
		    });
		}
	};
})

.directive('blShadowColorPreview', function() {
	return {
		restrict: 'A',
		link: function($scope, el) {
			$scope.$watch('props.color', function(newColor) {
				if (newColor == 'rgba(50, 50, 50, 0.75)') {
					el.css('background', 'url("public/images/transparent.png")');
				} else {
					el.css('background', newColor);
				}
			});
		}
	};
});

