'use strict';

angular.module('builder')

.factory('dom', ['$rootScope', 'undoManager', 'elements', 'themes', function($rootScope, undoManager, elements, themes) {
	
	var dom = {

		copiedNode: null,

		/**
		 * Html page meta data.
		 * 
		 * @type {Object}
		 */
		meta: {},

		/**
		 * Move selected node by one element in the specified direction.
		 * 
		 * @param  string  dir
		 * @param  DOM     node 
		 * 
		 * @return void
		 */
		moveSelected: function(dir, node) {
			if ( ! node) node = $rootScope.selected.parent;

			if (dir == 'down') {
				//check if there's an element after this one
				var next = $($rootScope.selected.node).next();
				
				if (next[0]) {

					//check if we can insert selected node into the next one
					if (elements.canInsertSelectedTo(next[0])) {
						next.prepend($rootScope.selected.node);
					} else {
						next.after($rootScope.selected.node);
					}

				} else {
					var parentParent = $($rootScope.selected.node).parent().parent();

					if (elements.canInsertSelectedTo(parentParent[0])) {
						$($rootScope.selected.node).parent().after($rootScope.selected.node);
					}
				}

				$rootScope.repositionBox('select');
			}

			else if (dir == 'up') {
				//check if there's an element before this one
				var prev = $($rootScope.selected.node).prev();

				if (prev[0]) {
					
					//check if we can insert selected node into the prev one
					if (elements.canInsertSelectedTo(prev[0])) {						
						prev.append($rootScope.selected.node);
					} else {				
						prev.before($rootScope.selected.node);
					}

				} else {
					var parentParent = $($rootScope.selected.node).parent().parent();
					
					if (elements.canInsertSelectedTo(parentParent[0])) {
						$($rootScope.selected.node).parent().before($rootScope.selected.node);
					}
				}
			
				return $rootScope.repositionBox('select');
			}  
		},

		/**
         * Append element user is currently dragging to the element users cursor is under.
         * 
         * @param  DOM node
         * @return void
         */
        appendSelectedTo: function(node, point) {
            if ( ! node) return true;
           	
            //check if we're not trying to drop a node inside it's child or itself
            if ($rootScope.selected.node == node || $rootScope.selected.node.contains(node)) {
                return true;
            }
        
            //get all the children of passed in node
            //todo might need to remove scope.active.node
            var contents = node.children, n;
            
            for (var i = 0, len = contents.length; i < len; i++) {
                n = contents[i];
                
                //check if users cursor is currently above any of the given nodes children
                if (this.above(n, point)) {

                    //if we can insert active element to given node and users
                    //cursor is above one of the children of that node then insert
                    //active element before that child and bail
                    if (elements.canInsertSelectedTo(node)) {
                        
                        //make sure we don't insert elements before body node
                        if (n.nodeName == 'BODY') {
                            n.appendChild($rootScope.selected.node);
                        } else {
                            n.parentNode.insertBefore($rootScope.selected.node, n);
                        }

                        //reposition context boxes 
                        return $rootScope.repositionBox('select');
                    }          
                }       
            }   
            
            //if users cursor is not above any children on the node we'll
            //just append active element to the node
            if (elements.canInsertSelectedTo(node)) {
                node.appendChild($rootScope.selected.node);
            }
            
            return true;
        },

        /**
         * Return whether or not given coordinates are above given element in the dom.
         * 
         * @param  DOM el
         * @param  Object point {x, y}
         * 
         * @return boolean
         */
        above: function(el, point) {

            if (el.nodeName !== '#text') {
                var offset = el.getBoundingClientRect();
                var width = el.offsetWidth;
                var height = el.offsetHeight;

                var box = [
                    [offset.left, offset.top], //top left
                    [offset.left + width, offset.top], //top right
                    [offset.left + width, offset.top + height], //bottom right
                    [offset.left, offset.top + height] //bottom left
                ];
                
                var beforePointY = box[0][1];
                var beforePointX = box[0][0];

                if (point.y < box[2][1]) {
                    return point.y < beforePointY || point.x < beforePointX
                }

                return false
            }
        },

		/**
		 * Replace builder iframe html with given one.
		 * 
		 * @param  {string} html
		 * @return void
		 */
		loadHtml: function(html) {
			if (html) {
				//parse out body contents and any assets from head that need to be included
				var bodyContents = html.match(/(<body[^>]*>)((.|[\r\n])+?)<\/body>/),
					assets = html.match(/<link.+?class="include.*?".+?">/g);


				if (bodyContents) {

					//parse body tag so we can transfer any classes or ids without replacing
					//the actual tag as there are event listeners attached to it.
  					var doc = new DOMParser().parseFromString(bodyContents[1]+'</body>', "text/html");

  					$rootScope.frameDoc.body.innerHTML = bodyContents[2];

  					if (doc) {
  						$rootScope.frameDoc.body.className = doc.body.className;
						$rootScope.frameDoc.body.id = doc.body.id;
  					}				
				} else {
                    $rootScope.frameBody.html('');
                }
  				
				if (assets && assets.length) {
				 	$rootScope.frameHead.append(assets.join("\n"));
				}
					
			} else {
				$rootScope.frameBody.html('');
			}
		},

		/**
		 * Return compiled html for currently active or passed in page.
		 * 
		 * @param  object|void   page        page to compile html for
		 * @param  boolean       includeCss  Whether or not all css should be included in compiled html
		 * @param  boolean       includeJs   Whether or not all js should be included in compiled html
         * @param  mixed         pageForJs   page that only js assets will be fetched from
		 * 
		 * @return string
		 */
		getHtml: function(page, includeCss, includeJs, pageForJs) {

			var head = '<head>',
				meta = page ? page : this,
				assets = '', body = '';

			head += '<meta charset="utf-8">'+
    				'<meta http-equiv="X-UA-Compatible" content="IE=edge">'+
    				'<meta name="viewport" content="width=device-width, initial-scale=1">';

			//add title if exists
			if (meta.title) {
				head += '<title>'+meta.title+'</title>';
				head += '<meta name="title" content="'+meta.title+'">';
			}

			//add meta description if exists
			if (meta.description) {
				head += '<meta name="description" content="'+meta.description+'">';
			}

			//add meta keywords if exists
			if (meta.tags) {
				head += '<meta name="keywords" content="'+meta.tags+'">';
			}
			
			//add links to needed assets
			if (page && page.html) {
				assets = page.html.match(/<link.+?class="include.*?".+?">/g);
			} else {
				assets = $rootScope.frameHead.html().match(/<link.+?class="include.*?".+?">/g);
			}			

			if (assets && assets.length) {
				var uniques = [];

				for (var i = assets.length - 1; i >= 0; i--) {
					if (uniques.indexOf(assets[i]) === -1) {
						uniques.push(assets[i]);
					}
				};
				
				head += uniques.join("\n");
			}
			
			head += '<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">';

			if (page) {
				var path = $rootScope.baseUrl+'/public/css/bootstrap.min.css';
				head += '<link href="'+path+'" rel="stylesheet">';
			} else {
				head += $rootScope.frameHead.find('#main-sheet')[0].outerHTML;
			}
			
			//add custom css if full html requested
			if (includeCss) {
				head += '<style id="elements-css">'+$rootScope.frameHead.find('#elements-css').html()+'</style>';
				
				if (page) {
					head += '<style id="editor-css">'+page.css+'</style>';
				} else {
					head += '<style id="editor-css">'+$rootScope.frameHead.find('#editor-css').html()+'</style>';

                    var inspectorCss = $rootScope.frameHead.find('#inspector-css')[0], combined = '';

                    if (inspectorCss) {
                        for (var ind = 0; ind < inspectorCss.sheet.cssRules.length; ind++) {
                            combined += inspectorCss.sheet.cssRules[ind].cssText;
                        }

                        head += '<style id="inspector-css">'+combined+'</style>';
                    }
				}

			}

			head += '</head>';
			
			//get body css
			if (page && page.html) {
				body = page.html.match(/(<body[^>]*>(.|[\r\n])+?<\/body>)/)[1];
			} else {
				body = $rootScope.frameDoc.body.outerHTML;
			}

			body = body.replace('contenteditable="true"', '');

			if (includeJs) {

                //include any script tags added by custom elements and make sure they
                //are included after jquery and bootstrap but before js from code editor
                var elementsScripts = body.match(/<script class="element-css".*>.*<\/script>/ig, '');
                body = body.replace(/<script class="element-css".*>.*<\/script>/ig, '');

                body += '<script src="public/js/vendor/jquery.js"></script>';
                body += '<script src="public/js/vendor/bootstrap/bootstrap.min.js"></script>';

                if (elementsScripts && elementsScripts.length) {
                    for (var i = 0; i < elementsScripts.length; i++) {
                        body += elementsScripts[i];
                    }
                }

                var pgForJs = pageForJs || page;

				if (pgForJs && pgForJs.js) {
					body += '<script>'+pgForJs.js+'</script>';
				}
			}

			//compile everything into complete html string
			return "<!DOCTYPE html>\n<html>"+head+body+'</html>';
		},

        /**
         * Converts preview nodes html to their actual html (iframe image to actual iframe) in the given string.
         *
         * @param html
         * @returns {string}
         */
        previewsToHtml: function(html) {
            return html.replace(/<img.+?data-src="(.+?)".+?>/gi, '<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="$1"></iframe></div>');
        },

		/**
		 * Set meta data for currently active page.
		 * 
		 * @param object meta
		 */
		setMeta: function(meta) {
			this.meta = meta;
		},

		/**
		 * Copy given node for later use or pasting.
		 * 
		 * @param  DOM   node
		 * @return void
		 */
		copy: function(node) {
			if (node && node.nodeName != 'BODY') {
				this.copiedNode = $(node).clone();
			}
		},

		/**
		 * Paste copied DOM node if it exists.
		 * 
		 * @param  DOM node
		 * @return void
		 */
		paste: function(node) {
			if (node && this.copiedNode) {

				//make sure we don't paste nodes after body
				if (node.nodeName == 'BODY') {
					$(node).append(this.copiedNode);
				} else {
					$(node).after(this.copiedNode);
				}

				//register a new undo command with undo manager
				var params = { node: this.copiedNode };
				params.parent = params.node.parent();
				params.parentContents = params.parent.contents();
		        params.undoIndex = params.parentContents.index(params.node.get(0));
				undoManager.add('insertNode', params);

				this.copiedNode = null;

				$rootScope.$broadcast('builder.html.changed');
			}
		},

		/**
		 * Copy and remove the given node.
		 * 
		 * @param  DOM node
		 * @return void
		 */
		cut: function(node) {
			if (node && node.nodeName != 'BODY') {
				this.copy(node);
				this.delete(node);
			}
		},

		/**
		 * Delete a node from the DOM.
		 * 
		 * @param  DOM   node
		 * @return void
		 */
		delete: function(node) {

			if (node && node.nodeName != 'BODY') {

				if (node.parentNode) {
					$rootScope.$apply(function() {$rootScope.selectNode(node.parentNode);});
				}
				
				//register a new undo command with undoManager before we remove the node
				var params = { node: node };
				params.parent = params.node.parentNode;
				params.parentContents = params.parent.childNodes;
				params.undoIndex = utils.getNodeIndex(params.parentContents, params.node);
				undoManager.add('deleteNode', params);

				$(node).remove();

				$rootScope.hoverBox.hide();
				$rootScope.repositionBox('select');

				$rootScope.$broadcast('builder.html.changed');
			}
		},

		clone: function(node) {
			this.copy(node);
	        this.paste(node);
		},

		wrapInTransparentDiv: function(node) {
			node = $(node);
			
			//insert given node contents into the transparent wrapper
			var wrapper = $(
				'<div class="transparent-background" style="background-color: rgba(0,0,0,0.8)"></div>'
			).append(node.contents());

			//append wrapper to node and get nodes padding
			var padding = node.append(wrapper).css('padding');

			//move padding from node to wrapper to avoid white space
			wrapper.css('padding', padding);
			node.css('padding', 0);

			$rootScope.$broadcast('builder.html.changed');
		},

		//tables
		
		/**
		 * Add a new row to the table before given node.
		 * 
		 * @type void
		 */
		addRowBefore: function(node) {
			var tr = $(node).closest('tr');

			tr.before(tr.clone());

			$rootScope.repositionBox('select');
		},

		/**
		 * Add a new row to the table after given node.
		 * 
		 * @type void
		 */
		addRowAfter: function(node) {
			var tr = $(node).closest('tr');

			tr.after(tr.clone());

			$rootScope.repositionBox('select');
		},

		addColumnAfter: function(node) {
			this.addColumn(node, 'after');
		},

		addColumnBefore: function(node) {
			this.addColumn(node, 'before');
		},

		/**
		 * Add a new column to the table.
		 * 
		 * @type void
		 */
		addColumn: function(node, direction) {
			if (node.nodeName !== 'TD') { return false };

			var node  = $(node),
				index = node.index(),
				table = node.closest('table');

			table.find('thead tr').children('th').each(function(i, v) {
				if (i === index) {
					$(v)[direction]($(v).clone());
					return false;
				}
			});

			table.find('tbody tr').each(function(i,v) {
				$(v).children('td').each(function(i, v) {
					if (i === index) {
						$(v)[direction]($(v).clone());
						return false;
					}
				});
			});

			$rootScope.repositionBox('select');
		}
	};

	return dom;

}]);