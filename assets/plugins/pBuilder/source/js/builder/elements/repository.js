'use strict';

angular.module('builder.elements')

.factory('elements', ['$rootScope', '$http', '$translate', 'draggable', function($rootScope, $http, $translate, draggable) {

	//default element config
    var defaults = {
        name: 'Generic',
        canModify: ['padding', 'margin', 'box', 'text', 'attributes', 'float', 'shadows', 'background'],
        canDrag: true,
        showWysiwyg: true,
        attributes: {},
        previewScale: 1,
        scaleDragPreview: true,
        resizable: true,
        types: ['flow'],
        validChildren: ['flow'],
    };

    var repo = {

        loaded: false,

        elements: {},

        getAll: function() {
            return this.elements;
        },

        getElement: function(name) {
            return this.elements[name];
        },

        /**
         * Check if given node accepts currently active element as a child.
         * 
         * @param  DOM node
         * @return boolean
         */
        canInsertSelectedTo: function(node) {
           
            if (node.nodeName == 'BODY') return true;

            if (node.nodeName == 'HTML') return false;

            //match given node to an element in element repository
            var el = repo.match(node);

            //if we've got an element match and it has any valid children check
            //if currently active element can be insered into given node
            if (el && el.validChildren && $rootScope.selected.element.types) {
                for (var i = el.validChildren.length - 1; i >= 0; i--) {
                    if ($rootScope.selected.element.types.indexOf(el.validChildren[i]) > -1) {
                        return true;
                    }
                };      
            }
            
            return false;
        },

        /**
         * Array of special cases conditions when selecting a DOM node.
         * 
         * @type {Array}
         */
        specialCases: [

            //if selecting label with parent with .checkbox should select parent instead.
            function(node, parent, classes, pClasses) {
                if (node.nodeName == 'LABEL' && pClasses.indexOf('checkbox') > -1) {
                    return node.parentNode;
                }
            },

            //if selecting .progress-bar, select parent instead
            function(node, parent, classes, pClasses) {
                if (classes.indexOf('progress-bar') > -1) {
                    return node.parentNode;
                }
            },

            //if selecting .container-fluid with .navbar parent, select parent instead
            function(node, parent, classes, pClasses) {
                if (classes.indexOf('container-fluid') > -1 && pClasses.indexOf('navbar') > -1) {
                    return node.parentNode;
                }
            },
        ],

        checkForSpecialCases: function(node) {
            if ( ! node ) { return false };

            //cache some needed node properties
            var parent = node.parentNode,
                classes = node.nodeName,
                parentClasses = parent ? parent.nodeName : '';

            //test node against every special case until the end or until we find a match
            for (var i = 0; i < repo.specialCases.length; i++) {
                var check = repo.specialCases[i](node, parent, classes, parentClasses);

                if (check) { return check; }
            };
        },

        /**
         * Match given DOM node to an element in elements repository.
         * 
         * @param  DOM     node        
         * @param  string  type    
         * @param  boolean matchParent
         * 
         * @return void
         */
        match: function(node, type) {

            if ( ! node || ! node.nodeName) {
                return false;
            }
            
            var check = repo.checkForSpecialCases(node);
            
            if (check) {
                if (type == 'hover') {
                    $rootScope.hover.node = check;
                } else if (type == 'select') {
                    $rootScope.selected.node = check;
                }
                node = check;
            }
           
            for (var n in this.elements) {
                var element = this.elements[n];

                //find by name attribute
                if (node.dataset.name) {
                    return this.getElement(node.dataset.name);
                }

                //find by input type
                if (node.type) {
                    var type = node.nodeName.toLowerCase()+'='+node.type;

                    for (var i = element.nodes.length - 1; i >= 0; i--) {
                        if (element.nodes[i] == type) {
                            return element;
                        }
                    };
                }

                //find by node name           
                var nodeName = node.nodeName.toLowerCase();
                for (var i = element.nodes.length - 1; i >= 0; i--) {
                    if (element.nodes[i] == nodeName) {
                        return element;
                    }
                };         

                //find by class
                if (node.className) {
                    var classes = node.className.split(/\s+/);

                    for (var i = 0; i < classes.length; i++) {

                        //if element has no class we'll bail
                        if ( ! element.class) continue;

                        //if element and passed in node classes match straight
                        //up we'll just return current element
                        if (classes[i] === element.class) {
                            return element;
                        }

                        //if we didn't match an element by this time we'll try to do it using 
                        //a wildcard so we can match bootstrap columns and similar stuff
                        if (element.class.indexOf('*') > -1 && classes[i].match(new RegExp(element.class.replace('*', '.*')))) {
                            return element;
                        }
                    };
                }         
            };
   
            //if we've got no matches by this point and we've got
            //a true flag, will try to match this nodes parent instead
            
            if (false) {//if (matchParent) {
                return repo.match(node.parentNode, type, true);

            //if no true flag passed we'll just return a generic object
            } else {
                var className = node.className.split(/\s+/)[0];
                
                if (className) {
                    defaults.name = className.replace('-', ' ');
                } else {
                    defaults.name = node.nodeName.toLowerCase();
                }
               
                return defaults;
            }  
        },

        /**
         * Register a new element with the builder.
         * 
         * @param {object} config
         */
        addElement: function(config) {

            //merge defaults and passed in element config objects
            var el = angular.extend({}, defaults, config);

            //we'll need both snake case and camel case names for the element
            el.camelName = config.name.toCamelCase();
            el.snakeName = config.name.replace(/([A-Z])/g, function($1){return "-"+$1.toLowerCase();});

            //push newly created element to all elements object
            this.elements[el.name] = el;

            //create a draggable from new element and append it to elements container in the DOM
            if (el.canDrag) {
                var description = $translate.instant(el.camelName+'Desc'),

                //compile list item html with description or without
                html = '<li data-name="'+el.name+'" data-frameworks="'+el.frameworks.toString()+'">'+
                '<div class="el-list-item"><div class="icon"><i class="icon icon-'+el.icon+'"></i></div><div class="text">'+$translate.instant(el.camelName)+'</div><p class="el-description">'+(description == el.camelName+'Desc' ? '' : description)+'</p></li>';

                draggable.create($(html).appendTo('#'+el.category+' .list-unstyled'), el);
            }
        },

        init: function() {      
            //register all the base builder elements
            for (var i = 0; i < baseBuilderElements.length; i++) {
                repo.addElement(baseBuilderElements[i]);
            }
        }

    };

  	return repo;
}]);