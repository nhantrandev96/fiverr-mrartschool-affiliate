'use strict';

angular.module('dragAndDrop')

.factory('iframeScroller', ['$rootScope', function($rootScope) {

	var scroller = {};

	/**
	 * Scroll iframe body when given y is above or below it.
	 * 
	 * @param  integer y
	 * @return void
	 */
	scroller.scroll = function (y) {
		var st = $rootScope.frameBody.scrollTop(),
			pointY = y - $rootScope.frameOffset.top + st;

		if ( ! scroller.sh) {
			scroller.sh = $('#frame-wrapper').height();
		}

        if (pointY - st - 10 <= $rootScope.frameOffset.top) {
            scroller.scrollFrameUp()
        } else if (pointY > st + scroller.sh - 80) {
        	scroller.scrollFrameDown();           
        } else {		        	
        	scroller.stopScrolling();
        }
	};

	/**
	 * Scroll iframe down by 40 pixels.
	 * 
	 * @return timeout id
	 */
	scroller.scrollFrameDown = function () {
    	clearInterval(scroller.scrollDownTimeout);
	    return scroller.scrollDownTimeout = setInterval(function () {
	        return scroller.setScrollTop($rootScope.frameBody.scrollTop() + 40)
	    }, 40)
    };

    /**
     * Scroll iframe up by 40 pixels.
     * 
     * @return timeout id
     */
    scroller.scrollFrameUp = function () {
        clearInterval(scroller.scrollUpTimeout);
        return scroller.scrollUpTimeout = setInterval(function () {
            return scroller.setScrollTop($rootScope.frameBody.scrollTop() - 40)
        }, 40)
    };

    /**
     * Clear all scrolling intervals.
     * 
     * @return boolean
     */
    scroller.stopScrolling = function() {
    	clearInterval(scroller.scrollDownTimeout);
        return clearInterval(scroller.scrollUpTimeout);
    };

    /**
     * Set given scroll on iframe body.
     * 
     * @param void
     */
    scroller.setScrollTop = function (newScrollTop) {
        newScrollTop = Math.max(0, newScrollTop);
        $rootScope.frameBody.scrollTop(newScrollTop);
    };

	return scroller;
}]);