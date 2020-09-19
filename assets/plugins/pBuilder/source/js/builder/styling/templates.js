angular.module('builder.styling')

.controller('TemplatesController', ['$scope', 'templates', 'themes', 'dom', 'css', 'project', function($scope, templates, themes, dom, css, project) {

	$scope.templates = templates;

	$scope.templateData = {
		name: '',
        type: 'create',       
        color: 'blue',
        eplaceContents: false,
        category: 'Landing Page',

        clear: function() {
        	this.name = '';
        	this.type = 'create';
        	this.replaceContents = false;
        },

        getUpdateData: function() {
        	var data = {
    			name: this.name,
    			color: this.color,
    			category: this.category,
    		};

    		if (this.replaceContents) {
    			data.pages = this.getPagesContent();
    		}

    		return data;
        },

        getCreateData: function() {

        	var data = {
        		pages: [],
        		name: this.name,
        		color: this.color,
        		category: this.category,
        		theme: themes.active.name ? themes.active.name : 'default',
        	};

        	data.pages = this.getPagesContent();

        	return data;
        },

        getPagesContent: function() {
        	var content = [];

        	//if we have more then 1 page then send css/html of each page to server
        	if (project.active.pages.length > 1) {    		
        		for (var i = 0; i < project.active.pages.length; i++) {
        			var page = project.active.pages[i];

        			content.push({ 
        				js: page.js,
    	   				css: page.css,
        				html: page.html,
        				tags: page.tags,
        				name: page.name,
        				title: page.title,
        				theme: page.theme,    				
        				description: page.description,
        				libraries: $.map(page.libraries, function(lib) { return lib.name } ),
        			});
        		};

        	//otherwise just grab the current html/css
        	} else {
        		content.push({ 
        			css: css.compile(),
        			html: dom.getHtml(), 
        			js: project.activePage.js,
        			libraries: $.map(project.activePage.libraries, function(lib) { return lib.name } ),
        			name: 'index',		
        		});
        	}

        	return content;
        }
    };

}])

.factory('templates', ['$rootScope', '$http', 'localStorage', 'project', function($rootScope, $http, storage, project) {
	
	var templates = {

		/**
		 * All available templates.
		 * 
		 * @type {Array}
		 */
		all: [],

		colors: [
			{name: 'black', value: 'black'},{name: 'blue', value: '#84BDDB'},{name: 'gray', value: '#eee'},{name: 'green', value: '#18BB9B'},
			{name: 'brown', value: '#5A4A3A'},{name: 'orange', value: '#DA5C4A'},{name: 'red', value: 'red'}, {name: 'yallow', value: '#FFDF60'},
			{name: 'white', value: '#FAFAFA'},{name: 'purple', value: '#B84B61'}

		],

		categories: ['Landing Page', 'Blog', 'Portfolio'],

		loading: false,

		save: function(template) {
			this.loading = true;

			return $http.post('pr-templates/', template).success(function(data) {
				
				project.createThumbnail().then(function(canvas) {			
					$http({
					    url: 'pr-templates/'+data.thumbId+'/save-image',
					    dataType: 'text',
					    method: 'POST',
					    data: canvas.toDataURL('image/png', 1),
					    headers: { "Content-Type": false }
					}).success(function() {
						templates.all.push(data);
						templates.loading = false;
					}).error(function() {
						templates.loading = false;
					})
					
					//remove iframe left behind by html2canvas
					$rootScope.frameBody.find('iframe').remove();
				});
			});
		},

		update: function(template, id) {
			if ( ! id) return false;

			this.loading = true;

			return $http.put('pr-templates/'+id, template).success(function(data) {

				if (data.thumbId) {
					project.createThumbnail().then(function(canvas) {			
						$http({
						    url: 'pr-templates/'+data.thumbId+'/save-image',
						    dataType: 'text',
						    method: 'POST',
						    data: canvas.toDataURL('image/png', 1),
						    headers: { "Content-Type": false }
						}).success(function() {
							for (var i = templates.all.length - 1; i >= 0; i--) {
								if (templates.all[i].id == data.id) {
									templates.all[i] = data;
								}
							};
							templates.loading = false;
						}).error(function() {
							templates.loading = false;
						});
						
						//remove iframe left behind by html2canvas
						$rootScope.frameBody.find('iframe').remove();
					});
				}
			});
		},

		delete: function(id) {
			return $http.delete('pr-templates/'+id).success(function(data) {
				
				for (var i = templates.all.length - 1; i >= 0; i--) {
					if (templates.all[i].id == id) {
						templates.all.splice(i, 1);
					}
				};		
				
			});
		},

		/**
		 * Fetch all available templates from server.
		 * 
		 * @return Promise|undefined
		 */
		getAll: function() {
			if ( ! templates.all.length) {
				return $http.get('pr-templates/').success(function(data) {
					templates.all.push.apply(templates.all, data);
				});
			}
		},

		/**
		 * Return a template matching given id.
		 * 
		 * @param  string/int id
		 * @return undefined/object
		 */
		get: function(id) {
			for (var i = templates.all.length - 1; i >= 0; i--) {
				if (templates.all[i].id == id) {
					return templates.all[i];
				}
			};
		},

	};

	return templates;
}])

.directive('blTemplateActions', ['$rootScope', '$http', '$translate', 'dom', 'css', 'themes', 'project', function($rootScope, $http, $translate, dom, css, themes, project) {
    return {
        restrict: 'A',
        link: function($scope, el) {
        	var modal = $('#save-template-modal'),
	            error = modal.find('.text-danger');
	            
        	//use template
            el.find('.use-template').on('click', function() {
            	alertify.confirm($translate.instant('useTemplateConfirmation'), function (e) {
				    if (e) {
		            	project.useTemplate($scope.selectedTemplate).success(function() {
		            		//close templates panel
		            		$rootScope.flyoutOpen = false;
		            	});  	 
				    }
				});
            });

            //edit template
            el.find('.edit-template').on('click', function(e) {
            	modal.modal({backdrop: false});

            	var template = $scope.templates.get($scope.selectedTemplate);

            	$scope.$apply(function() {
            		$scope.templateData.type = 'edit';
            		$scope.templateData.name = template.name;
            		$scope.templateData.color = template.color;
            		$scope.templateData.category = template.category;
            	});

            });

            //delete template
            el.find('.delete-template').on('click', function(e) {
            	alertify.confirm("Are you sure you want to delete this template?", function (e) {
				    if (e) {
				       	$scope.templates.delete($scope.selectedTemplate).success(function(data) {
				       		if ($scope.templates.all.length) {
				       			$scope.selectTemplate($scope.templates.all[0].id);
				       		}			       		
				       	}).error(function(data) {
				       		alertify.log(data, 'error', 2200);
				       	});
				    }
				});
            });

            //save page as template
            el.find('.save-page-as-template').on('click', function(e) { 
	            modal.modal({backdrop: false});
	            $scope.templateData.type = 'create';
            });

            //submit template data to server on modal submit button click
            modal.find('.btn-success').on('click', function(e) {

        		if ($scope.templateData.type == 'edit') {
        			var promise = $scope.templates.update($scope.templateData.getUpdateData(), $scope.selectedTemplate);
        		} else {
        			var promise = $scope.templates.save($scope.templateData.getCreateData());
        		}

        		promise.success(function(data) {
        			modal.modal('hide');
        			error.html('');
	                $scope.templateData.clear();
        		}).error(function(data) {
        			error.html(data);
        		}).finally(function() {
        			$scope.templates.loading = false;
        		});
        	});           
        }
    };
}])

//Compile and insert templates panel html into the dom on element click.
.directive('blRenderTemplates', ['$compile', 'dom', 'templates', function($compile, dom, templates) {
    return {
   		restrict: 'A',
      	link: function($scope, el) {
      		var templatesList = el.find('#templates-list');

	     	$scope.selectTemplate = function(id) {
	     		if ( ! $scope.doc) { return false };

      			$scope.selectedTemplate = id;

      			if ( ! $scope.head) {
      				$scope.head = $scope.doc.getElementsByTagName('head');
      			}

      			for (var i = templates.all.length - 1; i >= 0; i--) {
      				if (templates.all[i].id == id) {
      					$scope.doc.body.scrollTop = 0;
      					$scope.doc.open('text/html', 'replace');
						$scope.doc.write(dom.getHtml(templates.all[i].pages[0], true, false));
						$scope.doc.close();

						//prevent click on external links so user doesn't navigate away from preview frame
						$($scope.doc).find('a').off('click').on('click', function(e) {
							if ( ! e.currentTarget.href || e.currentTarget.href.indexOf('#') === -1) {
								e.preventDefault();
							}
						});

						break;
      				}
      			};
	     	};

	     	//load template preview into iframe on template figure click
      		templatesList.on('click', 'figure', function(e) {
      			$scope.$apply(function() {
      				$scope.selectTemplate(e.currentTarget.dataset.id);
      			});
      		});
   
      		var deregister = $scope.$watch('activePanel', function(name) {
      			if (name == 'templates') {

      				//fetch templates if not fetched already
      				if ( ! templates.all.length) {
      					templates.getAll().then(function(data) {
      						if (templates.all.length) {
      							$scope.selectTemplate(templates.all[0].id);
      						}
      					});
      				}

      				//insert template list html indo DOM
      				var html = $compile(
		      			'<ul class="list-unstyled" bl-template-selectable>'+
							'<li ng-repeat="template in templates.all" class="col-md-6 col-lg-4">'+
								'<figure data-id="{{ template.id }}" ng-class="{ active: selectedTemplate == template.id }">'+
									'<img ng-src="{{ baseUrl+\'/\'+template.thumbnail }}" class="img-responsive">'+
									'<figcaption>{{ template.name }}</figcaption>'+
								'</figure>'+
							'</li>'+
						'</ul>'
					)($scope);

	      			templatesList.append(html);

	      			//handle template preview iframe appending
	      			var iframe = $('<iframe class="scale-iframe" id="templates-preview-iframe"></iframe>');
	      			iframe.appendTo(el.find('#templates-preview'));
	      			iframe.attr('src', 'about:blank');

	      			iframe.load(function(e) {
						$scope.doc = iframe[0].contentWindow.document;
						iframe.unbind('load');
					}); 

	      			deregister();
      			}
      		});
      	}
    };
}])