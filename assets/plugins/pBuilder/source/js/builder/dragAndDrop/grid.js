angular.module('dragAndDrop')

.factory('grid', ['$rootScope', 'undoManager', function($rootScope, undoManager) {

	var grid = {

		/**
		 * Maximum column span for the grid.
		 * 
		 * @type {Number}
		 */
		maxSpan: 12,

		/**
		 * Variables for a row which currently is beinged edited.
		 * 
		 * @type {Object}
		 */
		active: { controlCols: [] },

		hideEditor: function(showToggleButton) {

			$rootScope.$apply(function() {
      			$rootScope.rowEditorOpen = false;
      		});

  			grid.editor && grid.editor.hide();

            if (grid.active.row) {
                grid.active.row.removeClass('editing');
            }

  			grid.active = { controlCols: [] };
  			$('#highlights').removeClass('row-editor-open');

  			if (showToggleButton) {
  				$('#edit-columns').css({ width: 200, height: 40, padding: 10 });
  			} else {
  				$('#edit-columns').css({ width: 200, height: 0, padding: 0 });
  			}
		},

		/**
		 * Return whether or not given node is a column.
		 * 
		 * @param  $DOM  node
		 * @return boolean
		 */
		isColumn: function(node) {
			if (node.get) { node = node.get(0); };

			if (node && node.className) {
				return node.className.indexOf('col-') > -1;
			}
		},

		/**
		 * Return whether or not given node is a row.
		 * 
		 * @param  $DOM  node
		 * @return boolean
		 */
		isRow: function(node) {
			if (node.get) { node = node.get(0); };

			if (node && node.className) {
				return node.className.indexOf('row') > -1;
			}
		},

		/**
		 * Return given columns span.
		 * 
		 * @param  $DOM node
		 * @return integer
		 */
		getSpan: function(node) {
			return parseInt(node.attr('class').match(/col-[a-z]+-([0-9]+)/)[1]);
		},

		/**
		 * Get column span numbers for currently active row.
		 * 
		 * @return array
		 */
		getSpans: function() {
			var spans = [];

			if (this.active.cols) {
				for (var i = 0; i < this.active.cols.length; i++) {
					spans.push(this.getSpan($(this.active.cols[i])));
				};
			}

			return spans;
		},

		/**
		 * Get total columns span for a row.
		 * 
		 * @type {object} row
		 * @return integer
		 */
		getTotalSpan: function(row) {
			var total = 0;

			for (var i = 0; i < this.getColumns(row).length; i++) {
				total += this.getSpan($(this.getColumns(row)[i]));
			}

			return total;
		},

		/**
		 * Reuturn number of columns given row has.
		 * 
		 * @param  DOM row 
		 * @return integer
		 */
		getNumberOfCols: function(row) {
			return this.getColumns(row).length;
		},

		/**
		 * Get an array of column nodes for given row.
		 * 
		 * @param  $DOM row 
		 * @return array
		 */
		getColumns: function(row) {
			return $.map(row.children(), function(node) {
				if (node.className.indexOf('col-') > -1) {
					return node;
				}
			});
		},

		/**
		 * Return given columns sibling, preffering next one.
		 * 
		 * @param  $DOM node
		 * @return $DOM
		 */
		getSibling: function(node) {
			var next = node.next();

			//preffer the next sibling 
			if (next.length) {
				return next;
			}

			return node.prev();
		},

		/**
		 * Equalize columns spans of the currently active row.
		 * 
		 * @return void
		 */
		equalizeColumns: function() {
			if (this.active.row) {

				var spans = this.getSpans(),
					len   = this.maxSpan / spans.length;

				//if the number of columns splits evenly nicely (no float)
				//we'll just apply the new span to all the columns
				if (len % 1 === 0) {
					for (var i = 0; i < this.active.cols.length; i++) {
						this.resize($(this.active.cols[i]), false, len);
					}
				}

				//otherwise we will apply the new span to all but 2 last columns
				else {
					var span = Math.ceil(len);
					var colsLen  = this.active.cols.length;				

					for (var i = 0; i < colsLen; i++) {
						
						//apply new column size - 1 to the column before last one
						if (colsLen-2 == i) {
							this.resize($(this.active.cols[i]), false, span-1);
						}

						//apply span of 1 to the last column
						else if (colsLen-1 == i) {
							this.resize($(this.active.cols[i]), false, 1);
						}

						//apply the new span to the rest of the columns
						else {
							this.resize($(this.active.cols[i]), false, span);
						}
					}
				}
			}
		},

		/**
		 * Return whether given column is wider then
		 * given number of spans or not.
		 * 
		 * @param  {int} span
		 * @param  {object} node
		 * 
		 * @return boolean
		 */
		widerThen: function(span, node) {
			if (this.isColumn(node)) {
				return this.getSpan(node) > span;
			}
		},

		/**
		 * Resize passed in column in the DOM.
		 * 
		 * @param  {object}    node
		 * @param  {string}  operator   + or -
		 * @param  {int} newSpan    column span
		 * 
		 * @return void
		 */
		resize: function(node, operator, newSpan) {
			if ( ! newSpan) { newSpan = 1; }
			
			node.attr('class', function(i, className) {
	  			return className.replace(/(col-[a-z]+-)([0-9]+)/, function(full, start, oldSpan) {
	  				if (operator) {
	  					return operator == '+' ? start+(parseInt(oldSpan)+newSpan) : start+(parseInt(oldSpan)-newSpan);
	  				}

	  				return start+newSpan;
	  			});
	  		});
		},

		/**
		 * Return whether or not we can resize column using given params.
		 * 
		 * @param  array   cols          array of column span numbers
		 * @param  string  dir           + or - (increment or decrement)
		 * @param  integer index         column that is being resized index
		 * @param  integer siblingIndex  sibling column (that needs to make space for main one) index
		 * 
		 * @return boolean
		 */
		canResize: function(cols, dir, index, siblingIndex) {

  			//perform the given operation on column spans array
  			if (dir == '+') {
  				cols[index]++;
  				cols[siblingIndex]--;
  			} else {
  				cols[index]--;
  				cols[siblingIndex]++;
  			}
  			
  			//if we have negative column spans or more then
  			//12 we can't resize this column so return false
  			var sum = 0;
  			for (var i = 0; i < cols.length; i++) {
  				var num = parseInt(cols[i]);
  				sum += num;
  				
  				if ( ! num || sum > 12) {
  					
  					//reroll the changes we have done
  					if (dir == '-') {
		  				cols[index]++;
		  				cols[siblingIndex]--;
		  			} else {
		  				cols[index]--;
		  				cols[siblingIndex]++;
		  			}
  					
  					return false;
  				}
  			};

  			return true;
		},

		/**
		 * Insert new column before or after the given one.
		 * 
		 * @param $DOM node
		 * @param dir  string
		 *
		 * @return void
		 */
		addNewColumn: function(node, dir) {

			if ( ! dir) { dir = 'Before' }
			dir = dir.ucFirst();
			
			var makeUndoCommand = function(newCol, currCol) {
				var params            = { node:newCol };
			 	params.oldNode        = currCol;
			 	params.resize         = grid.resize;
			 	params.parent         = params.node.parent();
			 	params.parentContents = params.parent.contents();
			 	params.undoIndex      = params.parentContents.index(params.node.get(0));

		        undoManager.add('insertColumn', params);
			};
			
			var nodeIndex = node.index(),
				siblings  = node.parent().children(),
				colsAfter = siblings.filter(function(siblingIndex) {
					return nodeIndex < siblingIndex;
				}),
				colsBefore = siblings.filter(function(siblingIndex) {
					return nodeIndex > siblingIndex;
				}),
				inserted = false;
				
			//first try to reduce the next column by one
			if (grid.widerThen(1, $(colsAfter[0])))
			{
				grid.resize($(colsAfter[0]), '-', 1);
				$('<div class="col-sm-1"></div>')['insert'+dir](node);
				inserted = true;
			}

			//next try to reduce the given column by one
			else if ( ! inserted && grid.widerThen(1, node))
			{
				grid.resize(node, '-', 1);
				$('<div class="col-sm-1"></div>')['insert'+dir](node);
				inserted = true;
			}

			//next loop trough all columns after given one and
			//reduce the first one that's wider then one
			if ( ! inserted) {
				for (var i = 0; i < colsAfter.length; i++) {
				 	if (grid.widerThen(1, $(colsAfter[i]))) {
				 		grid.resize($(colsAfter[i]), '-', 1);
				 		$('<div class="col-sm-1"></div>')['insert'+dir](node);
				 		inserted = true;
				 		break;
				 	}
			 	}
			}

			//finally loop trough all columns before given one and
			//reduce the first one that's wider then one
			if ( ! inserted) {
				for (var i = 0; i < colsBefore.length; i++) {
				 	if (grid.widerThen(1, $(colsBefore[i]))) {
				 		grid.resize($(colsBefore[i]), '-', 1);
				 		$('<div class="col-sm-1"></div>')['insert'+dir](node);
				 		inserted = true;
				 		break;
				 	}
			 	};
			}
		},

		applyPreset: function(preset) {
			if (angular.isArray(preset) && this.active.row) {

				this.active.row.html('');

				for (var i = 0; i < preset.length; i++) {
					this.active.row.append('<div class="col-sm-'+preset[i]+'"></div>');
				};

				this.active.cols = grid.active.row.children('[class^="col-"]').get();

				this.redrawResizers();
				this.redrawControls();
				$rootScope.repositionBox('select');
				this.redrawEditor();
			}
		},

		redrawEditor: function() {
			if ( ! this.editor) {
				this.editor = $('#row-editor');
			}
			
			var editorOffsetTop = this.active.row.offset().top + this.active.row.height() - $rootScope.frameBody.scrollTop(),
				position = {
					left: this.active.row.offset().left,
					overflow: 'hidden',
					display: 'block'
				};

			if ($rootScope.frameWrapperHeight < editorOffsetTop + this.editor.outerHeight()) {
				position.top = 'initial';
				position.bottom = 35;
			} else {
				position.top = editorOffsetTop;
				position.bottom = 'initial';
			}
			
			this.editor.css(position);
		},

		/**
		 * Redraw column resizers for the currently active row.
		 * 
		 * @return void
		 */
		redrawResizers: function() {
			var top = ((this.editor[0].getBoundingClientRect().top - $rootScope.selectBox[0].getBoundingClientRect().top) / 2) - 23;
			var r = '<div class="column-resizer"><div class="col-dragger"><i class="icon icon-resize-horizontal" style="top: '+top+'px"></i></div></div>';

			if ( ! this.resizers) {
				this.resizers = $('#column-resizers');
			}

			this.resizers.html('');

			for (var i = 0; i < grid.active.cols.length-1; i++) {
				var current = grid.active.cols[i],
					left    = current.offsetLeft - current.parentNode.offsetLeft + $(current).outerWidth(true);

				if (current.className.indexOf('offset-') > -1) {
					left = left - current.offsetLeft + current.parentNode.offsetLeft;
				}

				$(r).data('index', i).appendTo(this.resizers).css('left', left);
			};	
		},

		/**
		 * Redraw row editor column controls.
		 * 
		 * @return void
		 */
		redrawControls: function() {
      		var middle = '<div class="col-control"><i class="icon icon-trash delete-column"></i><i class="icon icon-plus-outline add-column"></i></div>',
      			first  = '<div class="col-control"><i class="icon icon-plus-outline add-column first"></i><i class="icon icon-trash delete-column first"></i><i class="icon icon-plus-outline add-column"></i></div>',
      			last   = '<div class="col-control"><i class="icon icon-trash delete-column last"></i><i class="icon icon-plus-outline add-column last"></i></div>',
      			oneCol = '<div class="col-control"><i class="icon icon-plus-outline add-column first"></i><i class="icon icon-trash delete-column"></i><i class="icon icon-plus-outline add-column last"></i></div>',
      			control = '';

      		if ( ! this.controls) {
      			this.controls = $('#row-editor .column-controls');
      		}

      		this.active.controlCols = [];
      		this.controls.html('');

      		grid.active.cols = grid.getColumns(grid.active.row);

			for (var i = 0; i < this.active.cols.length; i++) {

				if (this.active.cols.length === 1) {
					control = $(oneCol);
				} else if (i === 0) {
					control = $(first);
				} else if (i === this.active.cols.length - 1) {
					control = $(last);
				} else {
					control = $(middle);
				}
				
				var className = this.active.cols[i].className.match(/col-..-\d{1,2}(?:\s+|$)/)[0].trim();

				var node = control.data('index', i).addClass(className).css({
					width:  $(this.active.cols[i]).outerWidth(true),
					'padding-left': $(this.active.cols[i]).css('margin-left'),
				});

      			this.controls.append(node);
      			this.active.controlCols.push(node.get(0));
			};
		},

		/**
		 * Insert new column after the given one.
		 * 
		 * @param $DOM node
		 */
		addColumnAfter: function(node) {
			return grid.addNewColumn(node, 'after');	
		},

		/**
		 * Insert new column before the given one.
		 * 
		 * @param $DOM node
		 */
		addColumnBefore: function(node) {
			return grid.addNewColumn(node, 'before');
		},

		/**
		 * Delete given column and give its span to one of the siblings.
		 * 
		 * @param  $DOM node
		 * @return void
		 */
		deleteColumn: function(node) {
			var sibling = this.getSibling(node),
				span    = this.getSpan(node),
				offset  = node.attr('class').match(/col-..-offset-(\d{1,2})(?:\s+|$)/);

			if (offset) {
				span += parseInt(offset[1]);
			}
			
			node.remove();

			this.resize(sibling, '+', span);
		},

		/**
		 * Reset row editor state.
		 * 
		 * @type void
		 */
		dispose: function() {
			this.editor = false;
			this.controls = false;
			this.resizers = false;
			this.active = { controlCols: [] };
			$rootScope.rowEditorOpen = false;
		}
	};

	return grid;
}])

.directive('blRowEditor', ['$rootScope', 'grid', function($rootScope, grid) {
    return {
   		restrict: 'A',
      	link: function($scope, el) {

      		$scope.$on('builder.html.changed', function(e) {
      			if ($scope.rowEditorOpen) {		
      				setTimeout(function(e) {
      					grid.redrawEditor();
      					$scope.repositionBox('select');
      					grid.redrawResizers();
      				}, 500)
      			}
      		});

			el.find('.equalize-columns').on('click', function() {
				grid.equalizeColumns();			
				grid.redrawControls();
				grid.redrawResizers();
			});

      		el.on('click', '.delete-column, .add-column', function(e) {
      			var target = $(e.currentTarget),
      				parent = target.parent(),
  					index  = parent.data('index');

  				//deleting a column
  				if (target.hasClass('delete-column')) {
  					if (grid.getNumberOfCols(grid.active.row) > 1) {
  						grid.deleteColumn($(grid.active.cols[index]));
  						grid.deleteColumn(parent);
  					} 
  				}

  				//adding a column
  				else if (target.hasClass('add-column')) {

  					if (target.hasClass('first')) {
  						grid.addColumnBefore($(grid.active.cols[0]));
  						grid.addColumnBefore(parent);
  					} 
  					else {
  						grid.addColumnAfter($(grid.active.cols[index]));
  						grid.addColumnAfter(parent);
  					}
  				}
  				
      			//order matters as otherwise redrawing won't account
      			//for newly added/removed column
      			grid.redrawControls();
      			grid.redrawResizers();
      		});

      		$rootScope.$on('$stateChangeSuccess', function(e) {
      			grid.dispose();
      		});	
      	}
    };
}])

.directive('blToggleRowEditor', ['$rootScope', '$timeout', 'grid', function($rootScope, $timeout, grid) {
    return {
   		restrict: 'A',
      	link: function($scope, el) {
      		var context = $('#highlights');

      		if ( ! grid.editor) {
      			grid.editor = $('#row-editor');
      		}

      		//toggle 'edit columns' button on element reselect
      		$scope.$on('element.reselected', function() {
      			var node = $scope.selected.node;

      			if (grid.isColumn(node) || grid.isRow(node)) {

      				//cache some needed variables
	                grid.active.row = $(node).closest('.row');

	                //editor only works if total column span is 12 or less
	                if (grid.getTotalSpan(grid.active.row) > grid.maxSpan) {
	                	return false;
	                }

                    el.css({ width: 200, height: 40, padding: 10 });
	            } else {
	                el.css({ height: 0, padding: 0});
	            }
      		});

      		//on 'edit columns' button click innitiate row editor
      		el.on('click', function(e) {

      			if ( ! grid.active.row) {
      				grid.active.row = $($scope.selected.node).closest('.row');
      			}

      			var initRowEditor = function() {
      				grid.active.row.addClass('editing');
	                $scope.repositionBox('select');
	                context.addClass('row-editor-open'); 				

	      			//cache row that's being edited and it's columns
	      			$scope.selectNode(grid.active.row.get(0));
	      			grid.active.cols = grid.getColumns(grid.active.row);
	      			
	      			$scope.$apply(function() {
	      				$rootScope.rowEditorOpen = true;
	      			});
	      			
	      			grid.redrawEditor();
	      			grid.redrawResizers();
	      			grid.redrawControls();

	      			grid.active.originalHtml = grid.active.row.html();
      			};

      			//resize iframe to 100% first if not already so we have
      			//enough space on the screen to draw the editor
      			if ( ! $scope.frame.hasClass('full-width')) {
      				$scope.frame.removeClass().addClass('full-width');

	      			$timeout(function() {
	      				$scope.frameOffset = $scope.frame[0].getBoundingClientRect();
	      				initRowEditor();
	      			}, 450);
      			} else {
      				initRowEditor();
      			}
      		});

      		grid.editor.find('.close-row-editor').on('click', function(e) {
      			alertify.confirm('This will undo all the changes you have made to this row, are you sure you want to continue?', function (e) {
		            if (e) {
		            	grid.active.row.html(grid.active.originalHtml);
		            	grid.hideEditor(true);
		                $scope.repositionBox('select');

		            }
		        });
      		});

      		grid.editor.find('.save-and-close-row-editor').on('click', function(e) {    			
      			grid.hideEditor(true);
                $scope.repositionBox('select');
  	            $rootScope.$broadcast('builder.html.changed');
      		});

      		$rootScope.$on('builder.project.cleared', function(e) {
      			grid.hideEditor();
      		});
      	}
    };
}])

.directive('blRowPresets', ['grid', function(grid) {
    return {
   		restrict: 'A',
      	link: function($scope, el) {
      		var presets = [ [12], [6,6], [3,6,3], [4,4,4], [3,3,3,3], [3,9], [9, 3], [2,2,2,2,2,2], [6,2,4], [4,2,6] ];

      		for (var i = 0; i < presets.length; i++) {
      			var preset = $('<div class="row-preset" data-preset="'+presets[i]+'"></div>').appendTo(el);

      			for (var ind = 0; ind < presets[i].length; ind++) {
      				preset.append('<div class="row-preset-'+presets[i][ind]+'"></di>');
      				preset.tooltip({
      					placement: 'top',
      					title: presets[i].join('-')
      				});
      			};
      		};
      		
      		el.on('click', '.row-preset', function(e) {
      			grid.applyPreset(e.currentTarget.dataset.preset.split(','));
      		});
      	}
    };
}])