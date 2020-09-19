'use strict';
var builder = {};

angular.module('builder', ['pascalprecht.translate', 'angularFileUpload', 'ngAnimate', 'builder.projects', 'builder.elements', 'builder.editors', 'builder.wysiwyg', 'dragAndDrop', 'undoManager', 'builder.styling', 'builder.directives', 'builder.inspector', 'builder.settings'])

.config(['$translateProvider', function($translateProvider) {
    $translateProvider.useUrlLoader('translations.json');
    $translateProvider.preferredLanguage('en');
	$translateProvider.useSanitizeValueStrategy('escaped');
}])

.run(['$rootScope', function($rootScope) {
	$rootScope.isWebkit       = navigator.userAgent.indexOf('AppleWebKit') > -1;
	$rootScope.isIE           = navigator.userAgent.indexOf('MSIE ') > -1 || navigator.userAgent.indexOf('Trident/') > -1;
	$rootScope.keys           = keys;
	$rootScope.selectedLocale = selectedLocale;
	$rootScope.baseUrl        = window.baseUrl || window.location.origin + window.location.pathname;
    $rootScope.isDemo         = document.URL.indexOf('architect-lite.vebto.com') > -1;
    $rootScope.selected       = {};
}])

.factory('bootstrapper', ['$rootScope', 'project', 'elements', 'keybinds', 'settings', function($rootScope, project, elements, keybinds, settings) {

	var strapper = {

		loaded: false,

		eventsAttached: false,

		start: function() {
			this.initDom();
			this.initProps();
			this.initSidebars();			
			this.initSettings();

			if ( ! this.eventsAttached) {
				$rootScope.$on('builder.dom.loaded', function(e) {
                    setTimeout(function() {
                        strapper.initProject();
                        strapper.initKeybinds();
                        strapper.eventsAttached = true;
                    })
				});
			}

			this.loaded = true;
		},

        initProject: function() {
            project.load();
        },

		initDom: function() {		
			$rootScope.frame = $('#iframe');
			$rootScope.frame[0].src = 'about:blank';

			$rootScope.frame.load(function() {
				$rootScope.frameWindow    = $rootScope.frame.get(0).contentWindow;
				$rootScope.frameDoc       = $rootScope.frameWindow.document;
				$rootScope.frameBody      = $($rootScope.frameDoc).find('body');
				$rootScope.frameHead      = $($rootScope.frameDoc).find('head');				
				$rootScope.$broadcast('builder.dom.loaded');
			});
			
			$rootScope.frameOverlay   = $('#frame-overlay');
			$rootScope.hoverBox       = $('#hover-box');
			$rootScope.selectBox      = $('#select-box');
			$rootScope.selectBoxTag   = $rootScope.selectBox.find('.element-tag')[0];
			$rootScope.hoverBoxTag    = $rootScope.hoverBox.find('.element-tag')[0];
			$rootScope.selectBoxActions = document.getElementById('select-box-actions');
			$rootScope.hoverBoxActions = document.getElementById('hover-box-actions');
			$rootScope.textToolbar    = $('#text-toolbar');
			$rootScope.windowWidth    = $(window).width();
			$rootScope.inspectorCont  = $('#inspector');
			$rootScope.contextMenu    = $('#context-menu');
			$rootScope.linker         = $('#linker');
			$rootScope.inspectorWidth = $rootScope.inspectorCont.width();
			$rootScope.elemsContWidth = $("#elements-container").width();
			$rootScope.mainHead       = $('head');		
			$rootScope.body           = $('body');
			$rootScope.viewport       = $('#viewport');
			$rootScope.navbar         = $('nav');
			$rootScope.contextMenuOpen= false;
			$rootScope.activePanel    = 'export';
			$rootScope.flyoutOpen     = false;
			
			//set the iframe offset so we can calculate nodes positions
			//during drag and drop or sorting correctly		
			$rootScope.frameOffset = {top: 89, left: 234};
			$(document).ready(function() {
				setTimeout(function() {
					$rootScope.frameOffset = $rootScope.frame.offset();
					$rootScope.frameWrapperHeight = $('#frame-wrapper').height();
				}, 1000);	
			});
		},

		initSettings: function() {
			settings.init();
		},

		initSidebars: function() {
			elements.init();
		},

		initKeybinds: function() {
			keybinds.init();
		},

		initProps: function() {
			//information about currently user selected DOM node
			$rootScope.selected  = {

				//return selected elements html, prioratize preview html
				html: function(type) {
					if ( ! type || type == 'preview') {
						return this.element.previewHtml || this.element.html;
					} else {
						return this.element.html;
					}
				},

				getStyle: function(prop) {
					if (this.node) {
						return window.getComputedStyle(this.node, null).getPropertyValue(prop);
					}
				}
			};

			//information about node user is currently hovering over
			$rootScope.hover = {};

			//whether or not we're currently in progress of selecting
			//a new active DOM node
			$rootScope.selecting = false;	
		}
	};

	return strapper;
}]);