angular.module('builder.inspector', [])

.factory('inspector', ['$rootScope', 'css', 'undoManager', function($rootScope, css, undoManager) {

	var inspector = {

		/**
		 * Whether or not user is currently using range slider.
		 * 
		 * @type {Boolean}
		 */
		sliding: false,

		/**
		 * Enabled checkboxes.
		 * 
		 * @type {Object}
		 */
		checkboxes: {
			margin: [],
			padding: [],
			borderWidth: [],
			borderRadius: [],
		},

		/**
		 * Indexes of css string value (10px 10px 10px 10px)
		 * sides when split into an array.
		 * 
		 * @type {Object}
		 */
		indexes: { top: 0, right: 1, bottom: 2, left: 3 },

		/**
		 * On inspector style changes, save new css styles for selected
		 * element and make them undoable/redoable.
		 * 
		 * @param  string style 
		 * @param  mixed  newValue 
		 * @param  mixed  oldValue
		 * 
		 * @return void
		 */
		applyCss: function(style, newValue, oldValue) {
			var isObject = angular.isObject(newValue);
		
			//bail if no or incorrect values passed
			if (! newValue || oldValue === newValue) return true;
			
			//make sure we get object value and not reference
			var oldVal = isObject ? $.extend({}, oldValue) : oldValue;
			var newVal = isObject ? $.extend({}, newValue) : newValue;

			if ($rootScope.selected.path && ! $rootScope.selecting) {
				css.add(null, style, newVal, oldVal);
			}
			
			//make style change undoable, only if it's not changed via slider
			//as that would create a command for every pixel change
			if ($rootScope.selected && $rootScope.selected.path && ! inspector.sliding && ! $rootScope.selecting) {

				$rootScope.repositionBox('select');
				$rootScope.hoverBox.hide();
				
				undoManager.add('revertStyles', {
					property: style,
					oldStyles: oldVal,
					newStyles: newVal,
					node: $rootScope.selected.node,
					path: $rootScope.selected.path,
				});
			}
		},

		/**
		 * Toggle which directions will inspector slider affect.
		 * 
		 * @param  string style 
		 * @param  string dir
		 * 
		 * @return void
		 */
		toggleStyleDirections: function(prop, dir) {
			
			//toggle all direction if we get passed 'all'
			if (dir == 'all') {
				if (this.checkboxes[prop].length >= 4) {
					this.checkboxes[prop].length = 0;
				} else {
					if (prop == 'borderRadius') {
						this.checkboxes[prop].push('topRight', 'topLeft', 'bottomRight', 'bottomLeft');
					} else {
						this.checkboxes[prop].push('top', 'bottom', 'right', 'left');
					}
				}

				return $rootScope.$broadcast(prop+'.directions.changed');
			}

			var i = this.checkboxes[prop].indexOf(dir);

			if (i != -1) {
				this.checkboxes[prop].splice(i, 1);
			} else {
				this.checkboxes[prop].push(dir);
			}

			$rootScope.$broadcast(prop+'.directions.changed');
		},

		/**
		 * Clear any stored styles on the inspector.
		 * 
		 * @return void
		 */
		clearStyles: function() {
			for (var key in this.styles) {
				for (var prop in this.styles[key]) {
					if (! /(^_)/.test(prop) && prop !== 'property') {
						this.styles[key][prop] = null;
					}
			 	}
			}
		},

		/**
		 * Change existing css style on inspector to new one.
		 * 
		 * @param  string  prop    'border'
		 * @param  string  attr    'width'
		 * @param  array   sides   ['top', 'right']
		 * @param  string  value   10px
		 * @param  string  append  px
		 * 
		 * @return void
		 */
		changeStyle: function(prop, attr, sides, value, append) {

			//get a referance to style property on inspector
			if ( ! attr) {
				var property = this.styles[prop];
			} else {
				var property = this.styles[prop][attr];
			}
			
			//bail if no value or invalid params
			if (angular.isUndefined(property) || ! value) return true;

			//loop trough passed in value sides and replace them in
			//the old value string with the new value
			if (angular.isObject(property)) {

				for (var i = sides.length - 1; i >= 0; i--) {
					property[sides[i]] = append?value+append:value;
				}
			} else {
				var oldValue = property.split(' ');

				for (var i = sides.length - 1; i >= 0; i--) {
					oldValue[this.indexes[sides[i]]] = append?value+append:value;
				};
				
				this.styles[prop][attr] = oldValue.join(' ');
			}
		},

		applySliderValue: function(name, value, append) {
			
			if (name == 'borderWidth') {
				var prop = this.styles.border.width;
			} else if (name == 'borderRadius') {
				var prop = this.styles.border.radius;
			} else {
				var prop = this.styles[name];
			}

			//apply new value to the padding sides that are selected by user
			for (var i = this.checkboxes[name].length - 1; i >= 0; i--) {
				prop[this.checkboxes[name][i]] = append ? value+append : value;
			}

			if ($rootScope.selected.path && ! $rootScope.selecting) {
				css.add(false, name, prop, false, true);
			}
		},

		applyInputBoxValue: function(name, value, dir, append) {
			var old = css.getValueFor($rootScope.selected.selector, name);

			this.styles[name][dir] = value.replace(/[A-Za-z]/g, '')+'px';

			if ($rootScope.selected.path && ! $rootScope.selecting) {
				css.add(false, name, this.styles[name], old);
			}

			if ($rootScope.selected.path && ! inspector.sliding && ! $rootScope.selecting) {
				undoManager.add('revertStyles', {
					property: name,
					oldStyles: old,
					newStyles: $.extend({}, this.styles[name]),
					path: $rootScope.selected.path.slice(0),
				});
			}

			$rootScope.repositionBox('select');
		},

		applyBigInputBoxValue: function(name, value, append) {
			var old = css.getValueFor($rootScope.selected.selector, name);
				val = value.replace(/[A-Za-z]/g, '')+'px';

			this.styles[name] = {top: val, left: val, right: val, bottom: val};

			if ($rootScope.selected.path && ! $rootScope.selecting) {
				css.add(false, name, this.styles[name], old);
			}

			$rootScope.repositionBox('select');
		},

		styles: {
			padding: {},
			margin: {},
			border: {},
			color: {},
			attributes: {
				class: [],
				id: '',
				float: '',
			},
			text: {},
		}

	};

	return inspector;
}])

.controller('InspectorController', ['$scope', function($scope) {
	$scope.filter = {
		query: 'all',
	};

	$scope.categories = ['padding', 'margin', 'border', 'border-radius', 
	'text', 'shadows', 'background', 'attributes', 'all'];

}])

.directive('blInspectorHeader', ['settings', function(settings) {
    return {
        restrict: 'A',
        link: function($scope, el, attrs) {
        	var panels = $('.inspector-panel'),
        		select = $('#inspector-filter-select'),
        		name   = el.find('.panel-name'),
        		input  = el.find('input'),
				inputPanel = el.find('.panel-heading-input');

			//show/hide categories filter by default depending on user settings
			if (settings.get('showInspectorCategoriesFilterByDefault')) {
				select.removeClass('hidden');
			}
                
        	//toggle inspector filter select
        	el.find('.fa-filter').on('click', function(e) {
        		select.toggleClass('hidden');
        	});

        	//show only the panel user has selected
        	$scope.$watch('filter.query', function(category) {		
        		if (category == 'all') {
        			panels.removeClass('hidden');
        		} else {
        			panels.addClass('hidden');
        			$('#'+category+'-panel').removeClass('hidden');
        		}
        	});

        	//show css selector input on icon click
        	el.find('.fa-css3').on('click', function(e) {
               	inputPanel.addClass('open');
                name.removeClass('open');

                //wait for animation to complete
                setTimeout(function() {
                    input.focus();
                }, 450); 		
      		});

        	//hide css selector input on icon click
      		el.find('.fa-times').on('click', function(e) {
                inputPanel.removeClass('open');
                name.addClass('open');
      		});
        }
    }
}])

