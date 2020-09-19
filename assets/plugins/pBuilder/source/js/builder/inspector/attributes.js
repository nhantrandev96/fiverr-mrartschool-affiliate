angular.module('builder.inspector')

.controller('AttributesController', ['$scope', 'inspector', 'undoManager', function($scope, inspector, undoManager) {

	$scope.attributes = inspector.styles.attributes;

	$scope.customAttributes = {};

	//when new element is selected prepare it's custom attributes object for rendering in the DOM
	$scope.$on('element.reselected', function(e, node) {
		$scope.customAttributes = {};

		for (var attr in $scope.selected.element.attributes) {
			$scope.customAttributes[attr] = $.extend({}, $scope.selected.element.attributes[attr]);

			if ($scope.customAttributes[attr].onAssign) {
				$scope.customAttributes[attr].onAssign($scope);
			} else {
				$scope.defaultOnAssign($scope.customAttributes[attr]);
			}
		}
	});

	/**
	 * Default on assign action for custom options dropdown.
	 * 
	 * @param  object  attr  custom option objecy
	 * @return void
	 */
	$scope.defaultOnAssign = function(attr) {
		for (var i = attr.list.length - 1; i >= 0; i--) {

			var first = attr.list[i];

			if ($scope.selected.node.className.indexOf(attr.list[i].value) > -1) {
			    return attr.value = attr.list[i];
			}
		}

		//if we can't find any classes just set first
		//value on the select list as current value
		return attr.value = first;
	};

	/**
	 * default on change action for custom options dropdown.
	 * 
	 * @param  object  attr  custom option objecy
	 * @return void
	 */
	$scope.defaultOnChange = function(attr) {

		for (var i = attr.list.length - 1; i >= 0; i--) {
			$($scope.selected.node).removeClass(attr.list[i].value);
		};

		$($scope.selected.node).addClass(attr.value.value);

		setTimeout(function() {
			$scope.repositionBox('select');
		}, 300);
	};

	$scope.$watch('customAttributes', function(attr, oldAttr) {
		if ( ! $scope.selecting && ! $scope.dragging && $scope.selected && $scope.selected.node) {
			for (var name in attr) {
				if (oldAttr[name] && attr[name].value !== oldAttr[name].value) {

					$scope.makeUndoCommand(attr[name].onChange, oldAttr[name].value, attr[name].value);

					if (attr[name].onChange) {
						attr[name].onChange($scope, attr[name].value);
					} else {
						$scope.defaultOnChange(attr[name]);
					}
					
					$scope.repositionBox('select');
					$scope.$emit('builder.html.changed');
				}
			}
		}
	}, true);

	//create an undo command for custom elements attributes
	$scope.makeUndoCommand = function(func, oldVal, newVal) {
		undoManager.add('generic', {
			undo: function() {
				func($scope, oldVal);
			},
			redo: function() {
				func($scope, newVal);
			}
		});
	};

	/**
	 * Remove given class from active html node and
	 * from Inspector styles object.
	 * 
	 * @param  mixed
	 * @return void
	 */
	$scope.removeClass = function() {
		for (var i = arguments.length - 1; i >= 0; i--) {
			$($scope.selected.node).removeClass(arguments[i]);

			var position = $.inArray(arguments[i], inspector.styles.attributes['class']);

			if (~position) {
				inspector.styles.attributes['class'].splice(position, 1);
				$scope.$broadcast('builder.html.changed');
			}	
		};
	};

	/**
	 * Add given class to active html node and
	 * to inspector styles object.
	 * 
	 * @param string name
	 * @return mixed
	 */
	$scope.addClass = function(name) {
		if ( ! name || $.inArray(name, inspector.styles.attributes['class']) > -1) return true;

		$($scope.selected.node).addClass(name);		

		$scope.$apply(function() {
			inspector.styles.attributes['class'] = inspector.styles.attributes['class'].concat(name.split(' '));
			$scope.classInput = null;
		});

		$scope.repositionBox('select');
		$scope.$emit('builder.html.changed');
	};

	/**
	 * Add given float class to active html node
	 * and remove all other ones present on it.
	 * 
	 * @param string name
	 */
	$scope.addFloatClass = function(name) {
		$scope.removeClass('pull-left', 'pull-right', 'center-block');

		if (name && name != 'none') {
			$scope.addClass(name);
		}

		$scope.repositionBox('select');
		$scope.$broadcast('builder.html.changed');
	};

	//apply attributes to active html node on inspector attributes change
	$scope.$watchCollection('attributes', function(value, oldValue) {
		if (value !== oldValue) {
			for (var attr in value) {
				if (attr == 'class' || value[attr] === oldValue[attr]) {
					continue;
				} else if (attr == 'float') {
					if (oldValue.float) {
						$scope.addFloatClass(value.float);
					}
				} else {
					$($scope.selected.node).attr(attr, value[attr]);
				}
			}
		}
	});

	$scope.$on('element.reselected', function(e, node) {
		
		var classes = node.className.split(/\s+/),
			hidden  = $scope.selected.element.hiddenClasses;
		
		//remove any classes that need to be hidden and not removable by the user
		if (classes.length && hidden) {
			for (var i = 0; i < hidden.length; i++) {
				classes.splice(classes.indexOf(hidden[i]), 1);
			};
		}


		inspector.styles.attributes.class = classes;
		inspector.styles.attributes.id = node.id;
	

		var floats = ['pull-left', 'pull-right', 'center-block'];

		for (var i = floats.length - 1; i >= 0; i--) {
			if (node.className.indexOf(floats[i]) > -1) {
				inspector.styles.attributes.float = floats[i];
			} else {
				inspector.styles.attributes.float = 'none';
			}
		};
	});
}])

.directive('blElementVisibilityControls', ['elements', function(elements) {
    return {
   		restrict: 'A',
      	link: function($scope, el) {
      		var list = $('#visibility li');

      		//when a new element is selected reflect it's bootstrap 'hidden'
      		//classes in the inspector
      		$scope.$on('element.reselected', function(e) {
      			var classes = $scope.selected.node.className || '';

      			list.each(function(i, item) {
      				if (classes.indexOf('hidden-'+item.dataset.size) > -1) {
      					item.className = 'disabled';
      				} else {
      					item.className = '';
      				}
      			})
      		});

      		//on click add/remove 'hidden-x' class to currently selected element
      		el.on('click', 'li', function(e) {
      			var li = $(e.currentTarget);
      			var size = li.data('size');

      			if (li.hasClass('disabled')) {
      				$($scope.selected.node).removeClass('hidden-'+size);
      				li.removeClass('disabled');
      			} else {
      				$($scope.selected.node).addClass('hidden-'+size);
      				li.addClass('disabled');
      			}

      			$scope.$emit('builder.html.changed');
  				$scope.repositionBox('select');
      		});
      	}
    };
}])

.directive('blAddClassPanel', function() {
    return {
   		restrict: 'A',
      	link: function($scope, el) {
      		var panel = $('#addclass-flyout'),
      			input = panel.find('input');

      		//add class and hide input on confirm button click
      		el.find('.add-class').on('click', function(e) {
      			e.preventDefault();
      			$scope.addClass($scope.classInput);
      			panel.addClass('hidden');
      			return false;
      		});

      		//hide/show add-class panel
      		el.on('click', function(e) {
      			if (panel.hasClass('hidden')) {
  					panel.removeClass('hidden');
  					input.focus();
  				} else {
  					panel.addClass('hidden');
  				}   
      		});

      		//on enter click add class and hide input
      		input.keyup(function (e) {
			    if (e.keyCode == 13) {
			        $scope.addClass($scope.classInput);
			        panel.addClass('hidden');
			    }
			});
      	}
    };
})