'use strict';

angular.module('builder.directives', [])

.directive('blCloseFlyoutPanel', ['$rootScope', function($rootScope) {
    return {
   		restrict: 'A',
   		template: '<div title="Close Panel" class="flyout-close">'+
                	'<i class="icon icon-cancel-circled"></i>'+
           		  '</div>',
        replace: true,
      	compile: function(el) {
      		el.on('click', function() {
      			$rootScope.$apply(function() {
      				$rootScope.flyoutOpen = false;
      			});
      		});
      	}
    };
}])

.directive('blBuilder', ['$rootScope', 'elements', 'dom', function($rootScope, elements, dom) {
    return {
   		restrict: 'A',
      	link: function($scope) {
  			
  			$scope.$on('builder.dom.loaded', function() {
	      		$scope.frameHead.append('<base href="'+$scope.baseUrl+'">');
	      		$scope.frameHead.append('<link id="main-sheet" rel="stylesheet" href="public/css/bootstrap.min.css">');
	      		$scope.frameHead.append('<link rel="stylesheet" href="public/css/iframe.css">');
	      		$scope.frameHead.append('<link href="public/css/font-awesome.min.css" rel="stylesheet">');
	      		$rootScope.customCss = $('<style id="editor-css"></style>').appendTo($scope.frameHead);

	      		$($scope.frameDoc).on('scroll', function() {
	      			$scope.hoverBox.hide();
	      		});

                //init bootstrap tooltips
                $('[data-toggle="tooltip"]').tooltip({
                    container: 'body'
                });

                //listen for mousemove on iframe overlay
                $scope.frameOverlay.off('mousemove').on('mousemove', function(e) {

                    //see if we're dragging and element over iframe and if it actually
                    //moved since the last mouse move event
                    if ($scope.dragging && $scope.oldX != e.pageX && $scope.oldY != e.pageY) {

                        //account for the navbar/breadcrumbs/sidebar widths/heights when determining
                        //element position in the iframe
                        var coords = {x: e.pageX - $scope.frameOffset.left, y: e.pageY - $scope.frameOffset.top};

                        if ($scope.isIE) {
                            $scope.frameOverlay.hide();
                            var el = $scope.elementFromPoint(coords.x, coords.y);
                            $scope.frameOverlay.show();
                        } else {
                            var el = $scope.elementFromPoint(coords.x, coords.y);
                        }

                        //append dragged element to the one users cursor is hovering over
                        dom.appendSelectedTo(el, coords);

                        if ($scope.isWebkit) {
                            $scope.repositionBox('hover', el, elements.match(el));
                        }
                    }

                    $scope.oldX = e.pageX;
                    $scope.oldY = e.pageY;
                });
	      	});
      		
  			//prevent all scrolling on main document
      		$(document).on('scroll', function() {
      			$(document).scrollLeft(0);
      			$(document).scrollTop(0);
      		});
      	}
    };
}])


.directive('blToggleSidebar', ['settings', function(settings) {
    return {
        restrict: 'A',
        link: function($scope, el) {
        	var viewport = $('#viewport'),
        		parent   = el.parent(),
        		side     = el.hasClass('left') ? 'left' : 'right';

        	// open or close panels be default depending on user settings
        	if (side == 'right') { 		
        		if ( ! settings.get('openRightSidebarByDefault')) {
	        		viewport.addClass('right-collapsed');
	        		parent.css('right', '-234px');
	        	}
        	} else if (side == 'left') { 		
        		if ( ! settings.get('openLeftSidebarByDefault')) {
	        		viewport.addClass('left-collapsed');
	        		parent.css('left', '-234px');
	        	}
        	}

        	el.on('click', function() {
        		if (viewport.hasClass(side+'-collapsed')) {
        			viewport.removeClass(side+'-collapsed');
        			parent.css(side, '0');
        		} else {
        			viewport.addClass(side+'-collapsed');
        			parent.css(side, '-234px');
        		}

        		setTimeout(function() {
        			$scope.repositionBox('select');
        		}, 250);
        		
        	});
        }
   	}
}])

.directive('blPrettyScrollbar', function() {
    return {
        restrict: 'A',
        compile: function(el) {
            el.mCustomScrollbar({
            	theme: 'light-thin',
            	scrollInertia: 300,
            	autoExpandScrollbar: false,
            	autoHideScrollbar: true
            });
        }
   	}
})

.directive('blNavDropdown', function() {
    return {
        restrict: 'A',
        link: function($scope, el) {
            el.on('click', function() {
                el.siblings('li').toggle();
            });
        }
    }
})

.directive('blInputBoxes', ['$compile', '$timeout', function($compile, $timeout) {
    return {
        restrict: 'A',
        link: function($scope, el, attrs) {
        	var p = attrs.blInputBoxes;
        	
        	var html = '<div class="big-box col-sm-6">'+
				'<input ng-model="'+p+'All" ng-model-options="{ debounce: 300 }" ng-change="inspector.applyBigInputBoxValue(\''+p+'\', '+p+'All)">'+
			'</div>'+
			'<div class="small-boxes col-sm-6">'+
				'<div class="row">'+
					'<input ng-model="inspector.styles.'+p+'.top" ng-model-options="{ debounce: 300 }" ng-change="inspector.applyInputBoxValue(\''+p+'\', inspector.styles.'+p+'.top, \'top\')">'+
				'</div>'+
				'<div class="row">'+
					'<div class="col-sm-6">'+
						'<input ng-model="inspector.styles.'+p+'.left" ng-model-options="{ debounce: 300 }" ng-change="inspector.applyInputBoxValue(\''+p+'\', inspector.styles.'+p+'.left, \'left\')">'+
					'</div>'+
					'<div class="col-sm-6">'+
						'<input ng-model="inspector.styles.'+p+'.right" ng-model-options="{ debounce: 300 }" ng-change="inspector.applyInputBoxValue(\''+p+'\', inspector.styles.'+p+'.right, \'right\')">'+
					'</div>'+
				'</div>'+
				'<div class="row">'+
					'<input ng-model="inspector.styles.'+p+'.bottom" ng-model-options="{ debounce: 300 }" ng-change="inspector.applyInputBoxValue(\''+p+'\', inspector.styles.'+p+'.bottom, \'bottom\')">'+
				'</div>'+
			'</div>';

			el.html($compile(html)($scope));

			$scope.$on('element.reselected', function(e) {
				$timeout(function() {
					el.find('input').blur();
				}, 0, false);
			});
   		}
   	}
}])

.directive('blCheckboxes', ['$compile', function($compile) {
    return {
        restrict: 'A',
        link: function($scope, el, attrs) {
        	var p = attrs.blCheckboxes,
        		d = ['top', 'right', 'bottom', 'left'];

            var html = '<div class="pretty-checkbox pull-left">'+ 
			    '<input type="checkbox" id="'+p+'.all" ng-click="inspector.toggleStyleDirections(\''+p+'\', \'all\')">'+
			    '<label for="'+p+'.all"><span class="ch-all"></span><span class="unch-all"></span></label>'+  
			'</div>'+
			'<div class="pull-right">';

			for (var i = 0; i < 4; i++) {
				html+= '<div class="pretty-checkbox">'+  
				    '<input type="checkbox" id="'+p+'.'+d[i]+'" ng-click="inspector.toggleStyleDirections(\''+p+'\', \''+d[i]+'\')" ng-checked="inspector.checkboxes.'+p+'.indexOf(\''+d[i]+'\') !== -1">'+  
				    '<label for="'+p+'.'+d[i]+'"><span class="ch-'+d[i]+'"></span><span class="unch-'+d[i]+'"></span></label>'+   
				'</div>'
			};
	
			html+='</div>';

			el.html($compile(html)($scope));
        }
    }
}])

.directive('blRangeSlider', ['$rootScope', '$parse', 'inspector', function($rootScope, $parse, inspector) {

	return {
		restrict: 'A',
		link: function($scope, el, attrs) {
			var model = $parse(attrs.blRangeSlider);

			//initiate slider
			el.slider({
				min: 0,
		    	step: 1,
		    	max: attrs.max ? attrs.max : 100,
		      	range: "min",
		      	animate: true,
		    	slide: function(e, ui) {
		    		if (attrs.blRangeSlider.indexOf('props') > -1) {
		    			$scope.$apply(function() { model.assign($scope, ui.value); });
		    		} else {
		    			inspector.applySliderValue(attrs.blRangeSlider, ui.value, 'px');	
		    		}
		    		
		    	}
		    });
			
			//reset slider when user selects a different DOM element or different
			//style directions (top, bot, left, right)
			$scope.$on('element.reselected', function() { el.slider('value', 0) });
			$scope.$on(attrs.blRangeSlider+'.directions.changed', function() { el.slider('value', 0) });

			el.on("slidestart", function(event, ui) {
				inspector.sliding = true;
				$scope.$broadcast(attrs.blRangeSlider.replace(/[A-Z][a-z]+/g, '')+'.slidestart', attrs.blRangeSlider);

				//hide select and hover box while user is dragging
				//as their positions will get messed up
				$scope.selectBox.add($scope.hoverBox).hide();
			});

			el.on("slidestop", function(event, ui) {			
				$scope.$broadcast(attrs.blRangeSlider.replace(/[A-Z][a-z]+/g, '')+'.slidestop', attrs.blRangeSlider);
				$scope.repositionBox('select');
				inspector.sliding = false;
				$rootScope.$broadcast('builder.css.changed');
			});
		}
	}
}]);

