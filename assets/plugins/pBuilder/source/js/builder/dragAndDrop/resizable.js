'use strict';

var dnd = angular.module('dragAndDrop');

dnd.directive('blResizable', ['$rootScope', 'elements', 'undoManager', function($rootScope, elements, undoManager) {

	$.widget('ui.blResizable', $.ui.mouse, {

	  	_mouseStart: function(e) {

	  		this.isImage = $rootScope.selected.node.nodeName == 'IMG';

	  		this.originalSize = {
	  			height: $rootScope.selected.node.offsetHeight,
	  			width: $rootScope.selected.node.offsetWidth,
	  			left: this._num($rootScope.selected.getStyle('left')),
	  			top: this._num($rootScope.selected.getStyle('top')),
	  		};
	  		
	  		this.originalPos = {x: e.pageX + $rootScope.frameOffset.left, y: e.pageY + $rootScope.frameOffset.top};

	  		this.aspectRatio = ((this.originalSize.width / this.originalSize.height) || 1);
	  		
	        this._mouseDrag(e, true);	

	        return true;
	  	},
	  	_mouseDrag: function(e) {

	  		//constrain elements resizing to iframe
	  		if (e.target.id != 'frame-overlay' && e.target.className.indexOf('drag-handle') < 0) {
	  			return true;
	  		}

	  		var width  = this.originalSize.width + xdiff,
	  			height = this.originalSize.height + ydiff,
	  			xdiff  = (e.pageX + $rootScope.frameOffset.left) - this.originalPos.x,
        		ydiff  = (e.pageY + $rootScope.frameOffset.top) - this.originalPos.y,
        		calc   = this._calc[this.direction];

	  		//calculate the new elements height, width, top and left
			var data = calc.apply(this, [xdiff, ydiff]);
			data.overflow = 'hidden';
			
			//enforce minimum 10px height/width
			if ((angular.isNumber(data.height) && data.height < 10) || (angular.isNumber(data.width) && data.width < 10)) {
	  			return true;
	  		}

			//will need to add position relative so top/left works
			if (['n', 'w', 'nw', 'sw'].indexOf(this.direction) > -1) {
				data.position = 'relative';
			}

			//preserve aspect ratio if we're resizing an image or shift key is pressed
			if (this.isImage || e.shiftKey) {
				if (angular.isNumber(data.height)) {
					data.width = (data.height * this.aspectRatio);
				} else if (angular.isNumber(data.width)) {
					data.height = (data.width / this.aspectRatio);
				}

				if (this.direction === "sw") {
					data.left = this.originalSize.left + (this.originalSize.width - data.width);
					data.top = null;
				}
				if (this.direction === "nw") {
					data.top = this.originalSize.top + (this.originalSize.height - data.height);
					data.left = this.originalSize.left + (this.originalSize.width - data.width);
				}
			}

			for (var prop in data) {
				$rootScope.selected.node.style[prop] = data[prop];
			}
	  		
	  		$rootScope.repositionBox('select');

		},
	   	_mouseStop: function(e) {
	   		$rootScope.frameOverlay.css('cusror', 'default');
	    	$rootScope.frameOverlay.addClass('hidden');
	    	$rootScope.$broadcast('builder.html.changed');
	  	},
	  	_mouseCapture: function(e) {
	  		var target = $(e.target);

	  		//if selected node is column then bail so
	  		//column resizing widget will be innitiated instead
	  		if ($rootScope.selected.isColumn) {
				return false;
			}

	  		if (target.hasClass('drag-handle')) {

	  			this.direction = target.data('direction');

	  			$rootScope.frameOverlay.css('cursor', target.css('cursor'));

	  			return $rootScope.frameOverlay.removeClass('hidden');
	  		}
	  	},
		_init: function() {
		    return this._mouseInit();
		},
		_destroy: function() {
		    this._mouseDestroy();
		},
		_num: function(mixed) {
	  		if (mixed == 'auto') {
	  			return 0;
	  		} else if (mixed.match('px')) {
	  			return parseInt(mixed.replace('px', ''));
	 		}

	 		return parseInt(mixed);
	  	},
	  	_calc: {
			n: function(xdiff, ydiff) {
				return { top: this.originalSize.top + ydiff, height: this.originalSize.height - ydiff };
			},
			s: function(xdiff, ydiff) {
				return { height: this.originalSize.height + ydiff };
			},
			e: function(xdiff, ydiff) {
				return { width: this.originalSize.width + xdiff };
			},
			w: function(xdiff, ydiff) {
				return { left: this.originalSize.left + xdiff, width: this.originalSize.width - xdiff };
			},
			se: function(xdiff, ydiff) {
				return $.extend(this._calc.s.apply(this, arguments), this._calc.e.apply(this, arguments));
			},
			sw: function(xdiff, ydiff) {
				return $.extend(this._calc.s.apply(this, arguments), this._calc.w.apply(this, arguments));
			},
			ne: function(xdiff, ydiff) {
				return $.extend(this._calc.n.apply(this, arguments), this._calc.e.apply(this, arguments));
			},
			nw: function(xdiff, ydiff) {
				return $.extend(this._calc.n.apply(this, arguments),this._calc.w.apply(this, arguments));
			}
		},
	});

	return {
		restrict: 'A',
		link: function($scope, el) {
			el.blResizable({
				//prevent nodes whose text is being edited from being resized
				cancel: '[contenteditable="true"]'
			});
		},
	}

}]);