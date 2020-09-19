'use strict';

var dnd = angular.module('dragAndDrop', []);

dnd.factory('draggable', ['$rootScope', 'undoManager', 'iframeScroller', function($rootScope, undoManager, iframeScroller) {

	var draggable = {};

	/**
	 * Make passed DOM node draggable.
	 * 
	 * @param  $DOM node
	 * @param  string      name the name of element in elementsRepo
	 * @return Object      draggable
	 */
	draggable.create = function(node, el, context) {

		node.draggable({

			//check if we're innitiating draggable in iframe or not
			appendTo: context ? context.find('body') : 'body',
        	cursor: 'hand',
        	cancel: '',
        	scroll: false,
        	cursorAt: {
                left: -10,
                bottom: -10
            },
			helper: function() {
				$rootScope.selected.element = el;
				$rootScope.selected.node = $($rootScope.selected.html()).clone().get(0);

				//use simple div with element name as visual feedback for draggable
				if (el.dragHelper) {
					return $('<div id="drag-helper">'+el.name.toUpperCase()+'</div>');
				}
	            
	            //use scaled down clone of element as visual feedback
	            if (el.previewScale && el.previewScale !== 1 && el.scaleDragPreview) {
	            	return $($rootScope.selected.html()).appendTo($rootScope.body).css({
		            	transform: 'scale('+el.previewScale+')',
		            	'transform-origin': '0 100%',
		            });
	            } 

	            //use exact clone of element for feedback
	            return $rootScope.selected.html();           
			},		
			drag: function(e) {
				var parent = $rootScope.selected.node.parentNode;

				iframeScroller.scroll(e.pageY);

				if (parent) {
					this.command.params.parent = parent;
					this.command.params.parentContents = this.command.params.parent.childNodes;
		        	this.command.params.undoIndex = utils.getNodeIndex(this.command.params.parentContents, $rootScope.selected.node);
				}
			},
			start: function() {
				$rootScope.dragging = true;
				$('#frame-overlay').removeClass('hidden');
				$rootScope.frameBody.addClass('dragging');

				//create new undo command
				this.command = undoManager.add('insertNode', {
		       		node: $rootScope.selected.node,
		       	});
			},
			stop: function() {
				$rootScope.dragging = false;
				iframeScroller.stopScrolling();
				$rootScope.$apply(function() { $rootScope.selectNode(); });
				$('#frame-overlay').addClass('hidden');
				$rootScope.frameBody.removeClass('dragging');			
				$rootScope.$broadcast('builder.html.changed');

                //add custom element links to dom
                if (el.links) {
                    for (var i = 0; i < el.links.length; i++) {
                        $rootScope.frameHead.append('<link class="include element-css" rel="stylesheet" href="'+el.links[i]+'">')
                    }
                }

                //add custom element scripts to dom
                if (el.scripts) {
                    for (var i = 0; i < el.scripts.length; i++) {
                        $rootScope.frameBody.append('<script class="element-css" src="'+el.scripts[i]+'"></script>')
                    }
                }
			}
		});
	};

	return draggable;
}]);