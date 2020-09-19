'use strict';

angular.module('builder.projects', [])

.factory('project', ['$rootScope', '$http', '$timeout', 'css', 'dom', 'settings', 'themes', 'localStorage', function($rootScope, $http, $timeout, css, dom, settings, themes, localStorage) {
	
	var project = {

		/**
		 * Currently active project.
		 * 
		 * @type mixed
		 */
		active: false,

		/**
		 * Currentle active page.
		 * 
		 * @type mixed
		 */
		activePage: false,

		/**
		 * All projects user has access to.
		 * 
		 * @type {Array}
		 */
		all: [],

		/**
		 * Remove page by name
		 * 
		 * @param  {string} name
		 * @return promise
		 */
		removePage: function(name) {
            for (var i = project.active.pages.length - 1; i >= 0; i--) {
                var page = project.active.pages[i];

                if (page.name == name) {
                    project.active.pages.splice(i, 1);
                    project.changePage();
                    break;
                }
            }

            this.save();
		},

		/**
		 * Remove all pages from currently active project.
		 * 
		 * @return Promise
		 */
		removeAllPages: function() {
            this.active.pages = [];
            this.save();
		},

		/**
		 * Return projects page by name.
		 * 
		 * @param {string} name
		 * @return Object
		 */
		getPage: function(name) {
			for (var i = this.active.pages.length - 1; i >= 0; i--) {
				if (this.active.pages[i].name == name) {
					return this.active.pages[i];
				}
			}
		},

		/**
		 * Change currently active page to the given one.
		 * 
		 * @param  {string} name
		 * @param  {boolean}  noEvent  whether or not to fire page.changed event
		 * 
		 * @return void
		 */
		changePage: function(name, noEvent) {
			var page = false;

			//bail if project has no pages
			if ( ! project.active.pages || ! project.active.pages.length) {
				return false;
			}

			//if no name passed select first page
			if ( ! name) {
				name = project.active.pages[0].name;
			}
			
			//try to find page by given name
			for (var i = 0; i < project.active.pages.length; i++) {
				if (project.active.pages[i].name == name) {			
					page = project.active.pages[i];
				}
			}

			//if we couldn't find page by name just grab the first one
			if ( ! page) {
				page = project.active.pages[0];
			}

			//load the page
			dom.loadHtml(page.html);
			css.loadCss(page.css);
			dom.setMeta({
				title: page.title,
				tags: page.tags,
				description: page.description
			});

			project.activePage = page;

			localStorage.set('activePage', page.name);

			if ( ! noEvent) {
				$rootScope.$broadcast('builder.page.changed', page);
                $rootScope.selectBox.hide();
                $rootScope.hoverBox.hide();
			}
		},

		/**
		 * Load saved project into builder or init new one.
		 * 
		 * @return void
		 */
		load: function() {
            this.active = localStorage.get('architect-project');

            if (this.active) {
                this.changePage(localStorage.get('activePage'));
            } else {
                this.createNewProject();
                this.changePage('index');
            }
		},

        createNewProject: function() {
            this.active = {
                pages: [
                    {
                        name: 'index',
                        html: '<!DOCTYPE html><html><head><title></title></head><body></body></html>',
                    }
                ]
            }
        },

		/**
		 * Save current project in database.
		 * 
		 * @param  {array}  what
		 * @return promise
		 */
		save: function(what) {
			
			if ($rootScope.savingChanges || ! project.active) {
				return false;
			}
			
			$rootScope.savingChanges = true;

			if ( ! what) { what = 'all'; }

			var page = project.getPage(project.activePage.name);

			//get new html
			if (what == 'all' || what.indexOf('html') > -1) {
				page.html = style_html(dom.getHtml());
			}
			
			//get new css
			if (what == 'all' || what.indexOf('css') > -1) {
				page.css = css.compile();
			}

            localStorage.set('architect-project', this.active);

            $timeout(function() {
                $rootScope.savingChanges = false;
            }, 300)
		},

		/**
		 * Apply template with given id to active project.
		 * 
		 * @param  {int} id
		 * @return Promise
		 */
		useTemplate: function(id) {

			return $http.put('projects/'+this.active.id, { project: this.active, template: id }).success(function(data) {
				project.active = data;
				localStorage.set('activePage', 'index');
				project.changePage();

				if (angular.isString(project.activePage.libraries)) {
					project.activePage.libraries = JSON.parse(project.activePage.libraries);
				}			
			});            		      		
		},

		/**
		 * Request single project by id.
		 * 
		 * @param  {int} id
		 * @return promise
		 */
		get: function(id) {
			return $http.get('projects/'+id);
		},

		/**
		 * Get all projects current user has access to.
		 * 
		 * @return promise
		 */
		getAll: function() {
			return $http.get('projects').success(function(data) { 
				project.all = data;
			});
		},

		/**
		 * Delete given project.
		 * 
		 * @param  {object} pr
		 * @return Promise
		 */
		delete: function(pr) {
			return $http.delete('projects/'+pr.id).success(function(data) {
				for (var i = 0; i < project.all.length; i++) {
					if (project.all[i].id == pr.id) {
						project.all.splice(i, 1);
					}
				};
			}).error(function(data) {
				alertify.log(data, 'error', 2000);
			});
		},

		/**
		 * Clear all active projects html,css and js.
		 * 
		 * @return promise
		 */
		clear: function() {
			if ($rootScope.savingChanges) {
				return false;
			}

			$rootScope.savingChanges = true;

			for (var i = project.active.pages.length - 1; i >= 0; i--) {
				project.active.pages[i].html = '';
				project.active.pages[i].css = '';
				project.active.pages[i].js = '';
			}

			$rootScope.selected.path = false;

            $rootScope.frameBody.html('');
            $rootScope.selectBox.hide();
            $rootScope.hoverBox.hide();
            $rootScope.savingChanges = false;

            project.save();
            $rootScope.$broadcast('builder.project.cleared');
		}
	};

	$rootScope.$on('builder.html.changed', blDebounce(function(e) {
		if (settings.get('enableAutoSave') && ! $rootScope.dragging) { project.save(['html']); };
	}, settings.get('autoSaveDelay')));

	$rootScope.$on('builder.css.changed', blDebounce(function(e) {
		if (settings.get('enableAutoSave') && ! $rootScope.dragging) { project.save(['css']); };
	}, settings.get('autoSaveDelay')));

	$rootScope.$on('builder.js.changed', blDebounce(function(e) {
		if (settings.get('enableAutoSave') && ! $rootScope.dragging) { project.save(['js']); };
	}, settings.get('autoSaveDelay')));

	$rootScope.$on('builder.theme.changed', blDebounce(function(e) {
		if (settings.get('enableAutoSave') && ! $rootScope.dragging) { project.save(['theme']); };
	}, settings.get('autoSaveDelay')));

	return project;
}]);
