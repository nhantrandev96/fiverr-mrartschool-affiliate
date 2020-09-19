'use strict';

angular.module('builder.styling', [])

.directive('blFontsPagination', ['fonts', function(fonts) {

    return {
        restrict: 'A',  
        link: function($scope, el, attrs) {

        	//initiate pagination plugin
        	el.pagination({
		        items: 0,
		        itemsOnPage: fonts.paginator.perPage,
		        cssStyle: 'dark-theme',
		        onPageClick: function(num) {
		        	$scope.$apply(function() {
		        		fonts.paginator.selectPage(num);
		        	})
		        },
		        onInit: function(a) {
		        	$('.pagi-container > .simple-pagination').on('click', function(e) {
		        		e.preventDefault();
		        	});
		        }
		    });

		    //redraw pagination bar on total items change
		    $scope.$watch('fonts.paginator.totalItems', function(value) {
		    	if (value) { el.pagination('updateItems', value) }
		    });	    
        }
    }
}])

.factory('fonts', ['$rootScope', '$http', '$timeout', 'inspector', 'localStorage', function($rootScope, $http, $timeout, inspector, localStorage) {

	var fonts = {

		loading: false,

		paginator: {

			/**
			 * All available fonts.
			 * 
			 * @type array
			 */
			sourceItems: [],

			/**
			 * Fonts currently being shown.
			 * 
			 * @type array
			 */
			currentItems: [],

			/**
			 * Fonts to show per page.
			 * 
			 * @type integer
			 */
			perPage: 12,

			/**
			 * Total number of fonts.
			 * 
			 * @type integer
			 */
			totalItems: 0,

			/**
			 * Slice items for the given page.
			 * 
			 * @param  integer page
			 * @return void
			 */
			selectPage: function(page) {
				this.currentItems = this.sourceItems.slice(
					(page-1)*this.perPage, (page-1)*this.perPage+this.perPage
				);

				fonts.load();
			},

			/**
			 * Start the paginator with given items.
			 * 
			 * @param  array items
			 * @return void
			 */
			start: function(items) {
				this.sourceItems  = items;
				this.totalItems   = items.length;
				this.currentItems = items.slice(0, this.perPage);

				fonts.load();
			}
		},

		/**
		 * Apply given font to current active element.
		 * 
		 * @param  object font
		 * @return void
		 */
		apply: function(font) {

			//load given font into iframe
			this.load(font.family, $rootScope.frameHead);

			//apply new font family to inspector which will
			//handle adding css and undoManager command
			$timeout(function() {
				inspector.styles.text.fontFamily = font.family;
			});

			//hide the modal
			this.modal.modal('hide');
		},

		/**
		 * Fetch all available fonts from GoogleFonts API.
		 * 
		 * @return void
		 */
		getAll: function() {
			var self   = this,
				cached = localStorage.get('googleFonts'),
				key    = 'AIzaSyDhc_8NKxXjtv69htFcUPe6A7oGSQ4om2o';

			if (cached) {
				return self.paginator.start(cached);
			} 
			
			$http.get('https://www.googleapis.com/webfonts/v1/webfonts?sort=popularity&key='+key)
			.success(function(data) { 
				localStorage.set('googleFonts', data.items);
				self.paginator.start(data.items);
			});
		},

		/**
		 * Load given google fonts into the DOM.
		 * 
		 * @param  mixed names
		 * @return void
		 */
		load: function(names, context) {
			this.loading = true;

			//make an array of font names from current fonts
			//in the paginator if none passed
			if ( ! names) {
				names = $.map(fonts.paginator.currentItems, function(font) {
					return font.family;
				});

			//normalize names to array if string passed
			} else if ( ! angular.isArray(names)) {
				names = [names];
			}

			//load fonts either to main window or iframe
			if (context) {
				var head = context;
			} else {
				head = $rootScope.mainHead;
                $(head).find('#dynamic-fonts').remove();
			}

            var gFontsLink = $(head).find('#dynamic-fonts')[0];
            names = names.join('|').replace(/ /g, '+');

            if (gFontsLink) {
                gFontsLink.href += '|' + names;
            } else {
                //load the given fonts
                head.append(
                    '<link rel="stylesheet" class="include" id="dynamic-fonts" href="http://fonts.googleapis.com/css?family='+names+'">'
                );
            }

			this.loading = false;
		},

		modal: $('#fonts-modal'),
	};

	return fonts;
}]);