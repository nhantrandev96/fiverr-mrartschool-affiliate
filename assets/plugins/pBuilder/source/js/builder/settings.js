'use strict';

angular.module('builder.settings', [])

.controller('SettingsController', ['$scope', 'settings', function($scope, settings) {

	$scope.settings = settings;

	$scope.isBoolean = function(value) {
		return typeof value === 'boolean';
	};
}])

.factory('settings', ['$rootScope', 'localStorage', function($rootScope, localStorage) {

	var settings = {

		/**
		 * Wether or not we should save setting after a change.
		 * 
		 * @type {Boolean}
		 */
		pauseSaving: false,

		all: {
            contentBoxes: [
                {
                    name: 'enableHoverBox',
                    value: true,
                    category: 'contextBoxes',
                    description: 'Show/Hide box that appears when hovering over elements in the builder.',
                },
                {
                    name: 'enableSelectBox',
                    value: true,
                    category: 'contextBoxes',
                    description: 'Show/Hide box that appears when clicking an element in the builder.',
                },
                {
                    name: 'showWidthAndHeightHandles',
                    value: true,
                    category: 'contextBoxes',
                    description: 'Show/Hide circle handles used to change elements width and height by dragging them.',
                }
            ],
            autoSave: [
                {
                    name: 'enableAutoSave',
                    value: true,
                    category: 'autoSave',
                    description: 'Enable/Disable automatic saving when changes are made to the project or page. This may cause some delays on Firefox and Internet Explorer browsers.',
                },
                {
                    name: 'autoSaveDelay',
                    value: 800,
                    category: 'autoSave',
                    description: 'How long (in miliseconds) to wait before auto saving after changes are made.'
                }
            ],
            panels: [
                {
                    name: 'showElementPreview',
                    value: false,
                    category: 'Panels',
                    description: 'Should element preview container be visible in elements panel.'
                }
            ],
            elements: [
                {
                    name: 'enableFreeElementDragging',
                    value: false,
                    category: 'Elements',
                    description: 'When this is enabled you will be able to drag elements anywhere on the screen without any restrictions. Elements will be positioned absolutely so you should not use columns or rows with this mode. To use, first select an element then drag.'
                }
            ]
        },

		/**
		 * Do any work needed to bootstrap the settings.
		 * 
		 * @return void
		 */
		init: function() {
			var values = localStorage.get('settings');

			settings.pauseSaving = true;

			if (values) {
				for (var name in values) {
					settings.set(name, values[name]);
				}
			}

			settings.pauseSaving = false;
		},

		/**
		 * Save current settings to localStorage.
		 * 
		 * @return void
		 */
		save: function() {
			var values = {};

			for (var i = settings.all.length - 1; i >= 0; i--) {
				values[settings.all[i].name] = settings.all[i].value;
			}
			localStorage.set('settings', values);
		},

		/**
		 * Match given name to setting and return it's value.
		 * 
		 * @param  {string} name
		 * @return mixed
		 */
		get: function(name) {
            //loop trough each category and then trough each setting in that category
            for (var key in settings.all) {
                for (var j = 0; j < settings.all[key].length; j++) {
                    if (settings.all[key][j].name == name) {
                        return settings.all[key][j].value;
                    }
                }
            }
		},

		/**
		 * Change given setting to given value.
		 * 
		 * @param  {string} name
		 * @param  {mixed}  value
		 * 
		 * @return void
		 */
		set: function(name, value) {
            //loop trough each category and then trough each setting in that category
            for (var key in settings.all) {
                for (var j = 0; j < settings.all[key].length; j++) {
                    if (settings.all[key][j].name == name) {
                        settings.all[key][j].value = value;

                        if ( ! settings.pauseSaving) {
                            settings.save();
                            $rootScope.$emit('settings.changed', name, value);
                        }

                        break;
                    }
                }
            }
		}
	};

	return settings;
}])

//jquery toggler plugin
.directive('blSettingsToggler', ['settings', function(settings) {
    return {
   		restrict: 'A',
      	link: function($scope, el, attrs) {
      		var name = attrs.name;

      		el.toggles({
      			height: 30,
      			width: 70,
      			drag: false,
      			text: { on: 'Yes', off: 'No' },
      			on: attrs.blSettingsToggler == 'on'
      		});

      		el.on('toggle', function (e, value) {
				settings.set(name, value);
			});
      	}
    };
}])

//change active settings category on list item click
.directive('blSettingsCategorySwitchable', function() {
    return {
   		restrict: 'A',
      	link: function($scope, el) {
      		el.on('click', 'li', function(e) {
      			$scope.$apply(function() {
      				$scope.activeCategory = e.currentTarget.dataset.category;
      			});
      		});
      	}
    };
});