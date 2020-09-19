angular.module('builder.elements', [])

.controller('ElementsPanelController', ['$scope', '$rootScope', '$http', 'elements', function($scope, $rootScope, $http, elements) {

	//Search query to filter elements on.
	$scope.query = '';

	//By what framework elements should be filtered.
	$scope.framework = 'all';

    var listener = $scope.$on('builder.dom.loaded', function(e) {
        var mainStyle = $('<style id="elements-css"></style>').appendTo($scope.frameHead),
            customCss = '';

        $http.get('backend/load-custom-elements.php').success(function(data) {

            for (var i = data.length - 1; i >= 0; i--) {
                var config = eval(data[i].config);
                config.html = data[i].html;
                config.css = data[i].css;
                
                elements.addElement(config);

                customCss += "\n"+data[i].css;
            }

            mainStyle.append(customCss);
        });
    });

    var listener2 = $rootScope.$on('$translateChangeSuccess', function () {
        $('#elements-list .ui-draggable').remove();
        elements.init();
    });

    $scope.$on('$destroy', listener);
    $scope.$on('$destroy', listener2);
}])

/**
 * Toggle elements descriptions visibility on click.
 * 
 * @return void
 */
.directive('blToggleElDescriptions', function() {
    return {
   		restrict: 'A',
      	link: function($scope, el) {
      		el.on('click', function(e) {
      			el.toggleClass('inactive');
      			$('.el-description').toggle();
      		});
      	}
    };
})

/**
 * Filter elements in the panel on framework change.
 * 
 * @return void
 */
.directive('blElPanelFilterable', function() {
    return {
   		restrict: 'A',
      	link: function($scope, el) {
      		$scope.$watch('framework', function(name) {
      			el.find('.ui-draggable').each(function(i, item) {
      				var $item = $(item);

      				if (name == 'all' || item.dataset.frameworks.indexOf(name) > -1) {
      					$item.show().closest('.elements-box').show();
      				} else {
      					$item.hide();

      					if ($item.siblings(':visible').length == 0) {
						 	$item.closest('.elements-box').hide();
						}
      				}
      			});
      		});
      	}
    };
})

/**
 * Filter elements in the panel on search query change.
 * 
 * @return void
 */
.directive('blElPanelSearchable', function() {
    return {
   		restrict: 'A',
      	link: function($scope, el) {
      		$scope.$watch('query', function(query, oldQuery) {
                if (query === oldQuery) return;

      			el.find('.ui-draggable').each(function(i, item) {
      				var $item = $(item);

      				if ( ! query || $item.data('name').indexOf(query) > -1) {
                        el.find('.elements-box').removeClass('open');
      					$item.show().closest('.elements-box').show().addClass('open');
      				} else {
      					$item.hide();
      					
      					if ($item.siblings(':visible').length == 0) {
						 	$item.closest('.elements-box').hide().removeClass('open');
						}
      				}
      			});
      		});
      	}
    };
})

.directive('blElementPreview', ['elements', function(elements) {
    return {
        restrict: 'A',
        link: function($scope, el) {
            var frame = $('#el-preview-container > iframe'),
                description = $('#description-container'),
                elementsList = $('#elements-list'),
                doc, body, head, customCss, link;

            frame[0].src = 'about:blank';

            frame.on('load', function() {
                doc   = $(frame.get(0).contentWindow.document);
                body  = doc.find('body');
                head  = doc.find('head');

                head.append('<base href="'+$scope.baseUrl+'">');
                head.append('<link href="public/css/font-awesome.min.css" rel="stylesheet">');
                link = $('<link id="main-theme-sheet" rel="stylesheet" href="'+$scope.baseUrl+'/public/css/bootstrap.min.css">').appendTo(head)[0];
                
                $scope.$on('builder.theme.changed', function(e, theme) {
                    link.setAttribute('href', theme.path);
                });

                head.append('<link rel="stylesheet" href="'+$scope.baseUrl+'/public/css/iframe.css">');

                customCss = $('<style id="custom-css"></style>').appendTo(head);

                elementsList.on('mouseout', function(e) {
                    description.hide();
                });

                elementsList.on('mouseover', 'li', function(e) {
                    if ($scope.dragging) { return true };
                    
                    //get the element user hovered over
                    var el  = elements.getElement(e.currentTarget.dataset.name),
                        top = e.target.getBoundingClientRect().top,
                        desc = $(e.target).find('.el-description').text();
   
                    if (desc) {
                        description.text(desc).show();
                    }

                    //append any css that is needed to display the element
                    if (el.css) {
                        customCss.html(el.css);
                    }
     
                    //calculate width and height zoom percentage depending on preview scale
                    var zoom = 100 / el.previewScale + '%';

                    //scale the preview iframe
                    frame.css({
                        'transform-origin': '0 0',
                        transform: 'scale('+el.previewScale+')',
                        width: zoom,
                        height: zoom
                    });
                    
                    var prev = body.html(el.previewHtml || el.html).children(':first').addClass('center-preview')[0];

                    //center the node inside the preview iframe
                    if (prev) {
                        body.css({
                            'padding': (frame.height() - prev.getBoundingClientRect().height) / 2 + ' ' + 20,
                            overflow: 'hidden',
                        })  
                    } 
                })
            });
                

        }
    };
}]);