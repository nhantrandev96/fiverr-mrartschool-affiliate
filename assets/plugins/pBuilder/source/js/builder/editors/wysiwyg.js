'use strict';

angular.module('builder.wysiwyg', [])

/**
 * Holds font sizes and font families that can be applied to text.
 * 
 * @return Object
 */
.factory('textStyles', function(){
	return {
		fontSizes: [8, 9, 10, 11, 12, 14, 16, 18, 20, 22, 24, 26, 28, 36, 48, 72],
		fontWeights: [100, 200, 300, 400, 500, 600, 700, 800, 900, 'bold', 'bolder', 'light', 'lighter', 'normal'],
		fontAwesomeIconList: fontAwesomeIconsList,
		baseFonts: [
			{name: 'Impact', css: 'Impact, Charcoal, sans-serif'},
			{name: 'Comic Sans', css: '"Comic Sans MS", cursive, sans-serif'},
			{name: 'Arial Black', css: '"Arial Black", Gadget, sans-serif'},
			{name: 'Century Gothic', css: 'Century Gothic, sans-serif'},
			{name: 'Courier New', css: '"Courier New", Courier, monospace'},
			{name: 'Lucida Sans', css: '"Lucida Sans Unicode", "Lucida Grande", sans-serif'},
			{name: 'Times New Roman', css: '"Times New Roman", Times, serif'},
			{name: 'Lucida Console', css: '"Lucida Console", Monaco, monospace'},
			{name: 'Andele Mono', css: '"Andele Mono", monospace, sans-serif'},
			{name: 'Verdana', css: 'Verdana, Geneva, sans-serif'},
			{name: 'Helvetica Neue', css: '"Helvetica Neue", Helvetica, Arial, sans-serif'}
		]
	};
})

.controller('ToolbarController', ['$scope', 'textStyles', function($scope, textStyles) {
	$scope.href = 'http://';
	$scope.fontSizes = textStyles.fontSizes;
	$scope.baseFonts = textStyles.baseFonts;
	$scope.icons     = textStyles.fontAwesomeIconList;

	$scope.font = {
		size: '',
		family: ''
	};

	$scope.$watchCollection('font', function(newStyles, oldStyles) {
		for (var prop in newStyles) {
			if (newStyles[prop] && newStyles[prop] !== oldStyles[prop]) {
				$scope.applyStyle('font-'+prop, newStyles[prop]);
			}
		}
	});
}])

//show/hide icons list container on click
.directive('blToggleIconList', function() {
	return {
		restrict: 'A',
		link: function($scope, el) {
			el.on('click', function() {
				$('#icons-list').toggle();
				return false;
			});
		}
	}
})

.directive('blIframeTextEditable', ['$rootScope', 'elements', function($rootScope, elements) {
	return {
		restrict: 'A',
		link: function($scope) {
			var textToolbar  = $('#text-toolbar'),
				lastNode     = false,
				toolBarWidth = textToolbar.outerWidth(),
				toolbarHeight= textToolbar.outerHeight();

			textToolbar.addClass('hidden');

			var drawWysiwyg = function(x, y, node) {
				node    = node ? node : $scope.elementFromPoint(x, y - $scope.frameBody.scrollTop());
  				var matched = elements.match(node);
  	
  				if (matched.canModify.indexOf('text') > -1 && matched.showWysiwyg) {
  					$scope.selectBox.hide();

                    node.setAttribute('contenteditable', true);                  
                    node.focus();

                    var pos   = node.getBoundingClientRect(),
                    	top   = pos.top - toolbarHeight - 5,
                    	left  = pos.left + (pos.width - toolBarWidth) / 2,
                    	right = pos.left + toolBarWidth,
                    	rightEdge = $('#viewport').width(); 

                  	//position toolbar bottom if not enough space top
                  	// if (top < $scope.frameOffset.top) {
                  	// 	top += node.offsetHeight + 70;
                  	// }
 
                  	//position toolbar 15px from left panel if not enough space normally
                  	if (left < $scope.frameOffset.left) {
                  		left = 15;
                  	}

                  	//position toolbar 25px from right panel if not enough space normally
                  	if (rightEdge < right) {
                  		left = rightEdge - $scope.frameOffset.left - toolBarWidth - 25;
                  	}
                  	
  					textToolbar.removeClass('hidden').css({top: top, left: left});

  					lastNode = node;	
  				}	
			};

			$scope.$on('builder.dom.loaded', function(e) {

				//show on double click
	      		$scope.frameBody.off('dblclick').on('dblclick', function(event) {
                    if (event.target.nodeName !== 'BODY' && $(event.target).text().trim().length) {
                        drawWysiwyg(event.pageX, event.pageY);
                    }
	      		});

	      		//show on select box edit button click
	      		$scope.$on('builder.contextBox.editBtn.click', function(event) {
	      			if ($rootScope.selected.node.nodeName !== 'BODY' && $($rootScope.selected.node).text().trim().length) {
                        drawWysiwyg(event.pageX, event.pageY, $scope.selected.node);
                    }
	      		});

	      		//hide wysiwyg on scroll and remove contenteditable attribute from edited node
	      		$($scope.frameDoc).on('scroll', function(e) {
					if (textToolbar && lastNode && ! textToolbar.hasClass('hidden')) {
						textToolbar.addClass('hidden');
						lastNode.removeAttribute('contenteditable');
                        lastNode.blur();
					}			
				});
	      	});
		}
	}
}])


.directive('blFloatingToolbar', function() {
	return {
		restrict: 'A',
		link: function($scope, el) {
			rangy.init();

			//register rangy class appliers for basic text styling
			$scope.bold       = rangy.createCssClassApplier('strong', {elementTagName: "strong"});
			$scope.underline  = rangy.createCssClassApplier('u', {elementTagName: "u"});
			$scope.italic     = rangy.createCssClassApplier('em', {elementTagName: "em"});
			$scope.strike     = rangy.createCssClassApplier('s', {elementTagName: "s"});
			$scope.fontSelect = el.find('#toolbar-font');
			$scope.sizeSelect = el.find('#toolbar-size');

			el.find('#icons-list').on('click', '.icon', function(e) {
				var range   = rangy.getSelection($scope.frameDoc).getRangeAt(0),
					newNode = $scope.frameDoc.createElement('i');

				newNode.className = e.currentTarget.dataset.iconClass;			
				range.insertNode(newNode);
				
				el.find('#icons-list').hide();

			});
			
			//Add passed alignment class to active node and remove
			//all other alignment classes currently on it.
			$scope.align = function(direction) {
				$($scope.selected.node).removeClass(function (i,c) {
				    return (c.match (/(^|\s)text-\S+/g) || []).join(' ');
				}).addClass('text-'+direction);
			};
			
			//handle clicks on text styling/aligning buttons
			el.find('#toolbar-style').on('click', 'div', function(e) {
			    e.preventDefault();
			    var name = this.className;

			    if (name.indexOf('align') > -1) {
			    	$scope.align(name.replace('align-', ''));
			    } else if (name.indexOf('wrap-link') > -1) {
			    	$('#link-details').toggleClass('hidden');
			    } else {
			    	$scope[name].toggleSelection($scope.frameDoc);
			    }
			});

			//handle text selection wrapping/unwrapping with link
      		el.find('#link-details > .btn').on('click', function() {
      			var link = rangy.createCssClassApplier("link", {
                    elementTagName: "a",
                    elementProperties: {
                        href: $scope.href,
                        title: $scope.title
                    }
                });

                link.toggleSelection($scope.frameDoc);

                $('#link-details').toggleClass('hidden');
      		});

      		//reset font family and size select dropdowns to original values
      		//after user selects different text in contenteditable element
      		$scope.$on('builder.dom.loaded', function() {
	      		$scope.frameBody.mouseup(function(e) {
	      			if ($(e.target).is("[contenteditable='true']")) {
	      				$scope.sizeSelect.val('').prettyselect('refresh', true);
	      				$scope.fontSelect.val('').prettyselect('refresh', true);
	      			}		
	      		});
	      	});

      		//apply given style to current text selection by wrapping it in span
      		$scope.applyStyle = function(style, value) {
      			var attrs  = { style: style+': '+value },
      				sel    = rangy.getSelection($scope.frameDoc).toString();

      			//reset font styles to default if we're applying it
      			//to different text selection
      			if ($scope.oldSelection !== sel) {
      				$scope['font-size'] = false;
      				$scope['font-family'] = false;
      			}

      			//check for previous values on scope, this will allow us
      			//to apply both font size and font family to the same selection
      			if ($scope['font-size'] || $scope['font-family']) {
      				if (style == 'font-size') {
      					attrs.style += '; font-family: '+$scope['font-family'];
      				} else {
      					attrs.style += '; font-size: '+$scope['font-size'];
      				}
      			}

      			$scope[style] = value;
      			$scope.oldSelection = sel;

    			$scope.fontStyle = rangy.createCssClassApplier('font-style', {
	  				elementTagName: "span",
	  				elementAttributes: attrs
	  			});

    			//clear any previous selections so we don't wrap spans in spans
  				$scope.fontStyle.undoToSelection($scope.frameDoc);			

  				//if we get passed value apply it as font $scope.fontStyle to selection
  				if (value) {
  					$scope.fontStyle.applyToSelection($scope.frameDoc);
  				}		
      		}
		}
	}
});