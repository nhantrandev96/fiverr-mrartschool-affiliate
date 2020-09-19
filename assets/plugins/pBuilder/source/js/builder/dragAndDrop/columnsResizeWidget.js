'use strict';

angular.module('dragAndDrop')

.directive('blColumnsResizable', ['$rootScope', 'elements', 'undoManager', 'dom', 'grid', function($rootScope, elements, undoManager, dom, grid) {

	$.widget('ui.columnsResizable', $.ui.mouse, {
	  	_mouseStart: function(e) {

	  		this.dragger = $(e.target).closest('.column-resizer');
	  		this.index = this.dragger.data('index');
	  		
	  		//how much pixels cursor needs to move in order for us to snap and resize the column
	  		this.step = Math.ceil((8.33/100) * $rootScope.selected.node.offsetWidth);
	  		this.start = e.pageX;
	
	  		this.cols = $($rootScope.selected.node).children();

	  		this.col = $(this.cols.get(this.index));

	  		this.sibling = grid.getSibling(this.col);

	  		this.siblingIndex = this.cols.index(this.sibling);
	  		
	  		//create an array of only column spans as numbers
	 		this.nums = $.map(this.cols, function(v) {
				return grid.getSpan($(v));
			});
			  		
	  		$rootScope.frameOverlay.removeClass('hidden');
  		
	        this._mouseDrag(e, true);

	        return true;
	  	},
	  	_mouseDrag: function(e) {

	  		//backward
	  		if (this.oldX > e.pageX) {
  				if ((this.start - e.pageX) > this.step) {
	  				this.start = e.pageX;
	  				this._resizeColumn('-');
	  			}

	  		//forward
	  		} else if (this.oldX < e.pageX) {	
	  			if ((e.pageX - this.start) > this.step) {
	  				this.start = e.pageX;
	  				this._resizeColumn('+');
	  			}
	  		}

			this.oldX = e.pageX;

		    return true;	
		 },
	   	_mouseStop: function(e) {
	   		$rootScope.frameOverlay.addClass('hidden');
	  	},
		_init: function() {
		    return this._mouseInit();
		},
		_destroy: function() {
		    return this._mouseDestroy();
		},
		_mouseCapture: function(e) {
			var target = $(e.target);

			return target.is('.column-resizer') || target.is('.icon-resize-horizontal') || target.is('.col-dragger');
		},
	  	_resizeColumn: function(dir) {

	  		if (grid.canResize(this.nums, dir, this.index, this.siblingIndex)) {
	  			grid.resize(this.col, dir);
	  			grid.resize(this.sibling, dir == '+' ? '-' : '+');

	  			grid.redrawControls();
	  			grid.redrawResizers();
	  		}
	  	},
	});

	return {
		restrict: 'A',
		link: function($scope, el) {
			el.columnsResizable();
		},
	}

}]);