angular.module('builder.inspector')

.controller('BackgroundController', ['$scope', 'inspector', 'undoManager', function($scope, inspector, undoManager) {

	$scope.previews = {
		image: $('#image'),
		gradient: $('#gradient'),
		color: $('#fill-color'),
	};

	$scope.properties = {
		image: false,
		position: 'center center',
		repeat: 'repeat',
		color: false,
	};

	$scope.changeAlignment = function(alignment) {
		$scope.properties.position = alignment;
	}

	$scope.$watchCollection('properties', function(newProps, oldProps) {
		if (! $scope.selecting && ! $scope.dragging) {
			for(var prop in newProps) {
				if (newProps[prop] && newProps[prop] !== oldProps[prop]) {
					
					//handle previews in inspector - image
					if (newProps[prop].indexOf('url') > -1) {
						$scope.previews.image.css('background-image', newProps[prop]);
					//gradient
					} else if (newProps[prop].indexOf('gradient') > -1) {
						$scope.previews.gradient.css('background-image', newProps[prop]);
					//fill color
					} else {
						if (newProps[prop] && newProps[prop] != 'transparent' && newProps[prop] !== 'rgba(0, 0, 0, 0)') {
							$scope.previews.color.css('background', newProps[prop]);
						} else {
							$scope.previews.color.css('background', 'url("'+$scope.baseUrl+'images/transparent.png")');
						}
					}
					
					//use background instead of background-color so background image will get overwritten
					var style = prop == 'color' ? 'background' : 'background-'+prop;

					inspector.applyCss(style, newProps[prop], oldProps[prop]);
				}
			}		
		}
	});

	$scope.selectPreset = function(e) {
		console.log(e)
		if (e) {
			$scope.properties.image = $(e.target).css('background-image');
		}
	};

	//grab current css border styles for selected element so we can have a preview in inspector
	$scope.$on('element.reselected', function(e, node) {
		$scope.properties.image = node.style.backgroundImage;
		$scope.properties.color = node.style.backgroundColor;
	});

	//make only one background color undo command on slider stop event
	//so we don't flood it with thousands of similar color commands
	$scope.$on('properties.color.slidestart', function(e) {
		$scope.command = {
			property: 'background-color',
			node: $scope.selected.node,
			oldStyles: $scope.properties.color,
		}
	});
	$scope.$on('properties.color.slidestop', function(e) {
		$scope.command.newStyles = $scope.properties.color;
		undoManager.add('revertStyles', $scope.command);
	});

	$scope.textures = new Array(28);

	$scope.gradients = [
		'linear-gradient(to right, #959595 0%, #0D0D0D 46%, #010101 50%, #0A0A0A 53%, #4E4E4E 76%, #383838 87%, #1b1b1b 100%)',
		'linear-gradient(to right, #FF0000 0%, #FFFF00 50%, #ff0000 100%)',
		'linear-gradient(to right, #f6f8f9 0%, #E5EBEE 50%, #D7DEE3 51%, #f5f7f9 100%)',
		'linear-gradient(to right, #008080 0%, #FFFFFF 25%, #05C1FF 50%, #FFFFFF 75%, #005757 100%)',
		'linear-gradient(to right, #ff0000 0%, #000000 100%)',
		'linear-gradient(to bottom, #93cede 0%,#75bdd1 41%, #49a5bf 100%)',
		'linear-gradient(to right, #f8ffe8 0%, #E3F5AB 33%, #b7df2d 100%)',
		'linear-gradient(to right, #b8e1fc 0%, #A9D2F3 10%, #90BAE4 25%, #90BCEA 37%, #90BFF0 50%, #6BA8E5 51%, #A2DAF5 83%, #bdf3fd 100%)',
		'linear-gradient(to right, #f0b7a1 0%, #8C3310 50%, #752201 51%, #bf6e4e 100%)',
		'linear-gradient(to right, #ff0000 0%, #FFFF00 25%, #05C1FF 50%, #FFFF00 75%, #ff0000 100%)',
		'linear-gradient(to right, #ffb76b 0%, #FFA73D 50%, #FF7C00 51%, #ff7f04 100%)',
		'linear-gradient(to right, #ffff00 0%, #05C1FF 50%, #ffff00 100%)',
		'linear-gradient(to bottom, #febf01 0%,#febf01 100%)',
		'linear-gradient(to bottom, #fcfff4 0%,#e9e9ce 100%)',
		'linear-gradient(to bottom, #49c0f0 0%,#2cafe3 100%)',
		'linear-gradient(to bottom, #cc0000 0%,#cc0000 100%)',
		'linear-gradient(to bottom, #73880a 0%,#73880a 100%)',
		'linear-gradient(to bottom, #627d4d 0%,#1f3b08 100%)',
		'linear-gradient(to bottom, #b8c6df 0%,#6d88b7 100%)',
		'linear-gradient(to bottom, #9dd53a 0%,#a1d54f 50%,#80c217 51%,#7cbc0a 100%)',
		'linear-gradient(to bottom, #b8c6df 0%,#6d88b7 100%)',
		'linear-gradient(to bottom, #ff3019 0%,#cf0404 100%)',
		'linear-gradient(to bottom, #e570e7 0%,#c85ec7 47%,#a849a3 100%)',
		'linear-gradient(to bottom, #ffffff 0%,#f3f3f3 50%,#ededed 51%,#ffffff 100%)',
	];
}])

.directive('blShowImgContainer', ['$timeout', function($timeout) {
	return {
		restrict: 'A',
		link: function($scope, el, attrs, controller) {
			var list = $('#background-flyout-panel'),
				arrow = $('#background-arrow'),
				presets = { texture: $('#texturePresets'), gradient: $('#gradientPresets') };

			var togglePresetCont = function(name) {
				presets.texture.hide();
				presets.gradient.hide();

				if (name == 'image') name = 'texture';

				presets[name].show();
			};

			//hide panel on X click in the header
			list.find('.bl-panel-btns').on('click', function(e) {
				list.addClass('hidden');
				arrow.hide();
			});

			//on gradient or texture click in inspector position and show fly out pane
			el.on('click', function(e) {
				var offset = $(e.currentTarget).offset().top;
				list.removeClass('hidden');
				togglePresetCont(e.currentTarget.id);
				arrow.css('top', offset+12).show();

                var offsetTop = offset-(list.height()/2)+40;

                if (offsetTop < 1) {
                    offsetTop = 5;
                }

                list.css('top', offsetTop);

				//hide color picker container if exists
				if ($scope.colorPickerCont) {
					$scope.colorPickerCont.addClass('hidden');
				}
			});
		}
	};
}])

.directive('blImgAlignmentGrid', ['$compile', function($compile) {
	return {
		restrict: 'A',
		template: 
		'<div ng-class="{active: properties.position == \'top left\'}" ng-click="changeAlignment(\'top left\')" class="alignment-box"></div>'+
		'<div ng-class="{active: properties.position == \'top center\'}" ng-click="changeAlignment(\'top center\')" class="alignment-box"></div>'+
		'<div ng-class="{active: properties.position == \'top right\'}" ng-click="changeAlignment(\'top right\')" class="alignment-box"></div>'+
		'<div ng-class="{active: properties.position == \'center left\'}" ng-click="changeAlignment(\'center left\')" class="alignment-box"></div>'+
		'<div ng-class="{active: properties.position == \'center center\'}" ng-click="changeAlignment(\'center center\')" class="alignment-box"></div>'+
		'<div ng-class="{active: properties.position == \'center right\'}" ng-click="changeAlignment(\'center right\')" class="alignment-box"></div>'+
		'<div ng-class="{active: properties.position == \'bottom left\'}" ng-click="changeAlignment(\'bottom left\')" class="alignment-box"></div>'+
		'<div ng-class="{active: properties.position == \'bottom center\'}" ng-click="changeAlignment(\'bottom center\')" class="alignment-box"></div>'+
		'<div ng-class="{active: properties.position == \'bottom right\'}" ng-click="changeAlignment(\'bottom right\')" class="alignment-box"></div>',
	};
}]);