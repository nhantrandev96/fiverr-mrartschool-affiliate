angular.module('builder.directives').directive('blThemeCreator', ['$rootScope', '$http', 'localStorage', function($rootScope, $http, localStorage) {
    return {
        restrict: 'A',
        link: function($scope) {
            var iframe = $('<iframe id="theme-creator-iframe" src="themePreview.html"></iframe>'),
                list   = $('.vars-groups-list'),
                picker = $('#bootstrap-theme-creator .sp-container'),
                pickerCont = false,
                button = $('#create-new-theme-button'),
                booted = false, customStyles = false;

            button.on('click', function() {
                $scope.$apply(function() {
                    $scope.openPanel('themesCreator');
                })
            });

            //bootstrap theme creator
            var init = function(vars) {
                $rootScope.activePanel = 'themesCreator';
                $('.theme-creator-preview').append(iframe);

                //get reference to some iframe window vars on load
                iframe.load(function() {
                    $scope.doc   = $(iframe[0].contentWindow.document);
                    $scope.less  = iframe[0].contentWindow.less;
                    customStyles = $('<style id="custom-less"></style>').appendTo($scope.doc.find('head'));

                    //apply any passed in less variables
                    if (vars) { $scope.modifyVars(vars, true); };

                    var bootstrapVars = localStorage.get('bootstrap-vars');

                    if (bootstrapVars) {
                        $scope.$apply(function() {
                            $scope.bootstrap.defaultVars = $.extend(true, {}, bootstrapVars);
                            $scope.bootstrap.currentTheme = $.extend(true, {}, bootstrapVars);

                            //set first variable group as active one
                            for (var name in $scope.bootstrap.currentTheme) {
                                $scope.bootstrap.activeGroup = name;
                                $scope.bootstrap.activeVars = $scope.bootstrap.currentTheme[name];
                                break;
                            }
                        });
                    } else {
                        $http.get('pr-themes/bootstrap-vars').success(function(data) {

                            localStorage.set('bootstrap-vars', data);

                            $scope.bootstrap.defaultVars = $.extend(true, {}, data);
                            $scope.bootstrap.currentTheme = $.extend(true, {}, data);

                            //set first variable group as active one
                            for (var name in $scope.bootstrap.currentTheme) {
                                $scope.bootstrap.activeGroup = name;
                                $scope.bootstrap.activeVars = $scope.bootstrap.currentTheme[name];
                                break;
                            }
                        });
                    }

                    //initiate custom less editor
                    $scope.lessEditor = ace.edit('custom-less-editor');
                    $scope.lessEditor.setTheme('ace/theme/dawn');
                    $scope.lessEditor.getSession().setMode('ace/mode/less');

                    $scope.lessEditor.getSession().on('change', blDebounce(function() {
                        $scope.customLess = $scope.lessEditor.getValue();
                        $scope.less.render($scope.customLess).then(function(css, error) {
                            if (css.css) {
                                customStyles.html(css.css)
                            }
                        });

                    }, 500));

                    //make sure themes custom less gets rendered if it has any
                    if ($scope.customLess) {
                        $scope.lessEditor.setValue($scope.customLess, -1);
                    }

                    //change theme name in iframe so correct use is used for thumbnail
                    $scope.$watch('editing.name', function(newName, oldName) {
                        if (newName && newName.length > 3) {
                            $scope.doc.find('#preview-screen h1').text(newName);
                        }
                    });

                    iframe.unbind('load');
                });

                //initiate theme creator color picker
                var picker = $('#theme-creator-color-picker').spectrum({
                    flat: true,
                    showAlpha: true,
                    showPaletteOnly: true,
                    togglePaletteOnly: true,
                    palette: colorsForPicker,
                    appendTo: $('#theme-creator-color-picker'),
                });

                pickerCont = $('#bootstrap-theme-creator .sp-container');

                //hide picker on choose button click or outside it
                $('#bootstrap-theme-creator').add(pickerCont.find('.sp-choose')).on('click', function(e) {
                    pickerCont.addClass('hidden');
                });

                //apply new color to less variable
                picker.on('dragstop.spectrum change.spectrum', function(e, color) {
                    $scope.modifyVars(color.toRgbString());
                });

                booted = true;
            };

            button.one('click', function(e) {
                if ( ! booted) { init(); };
            });

            //on editing theme change in themes factory load it into the theme editor
            $scope.$watch('themes.editing', function(newTheme) {
                if ( ! newTheme) return;

                $scope.editing.theme = newTheme;
                $scope.editing.name = newTheme.name;
                $scope.editing.type = newTheme.type;
                $scope.openPanel('themesCreator');
                $scope.customLess = newTheme.customLess;

                if ( ! booted) {
                    init(JSON.parse($scope.editing.theme.vars));
                } else {
                    $scope.modifyVars(JSON.parse($scope.editing.theme.vars));

                    if ($scope.customLess) {
                        $scope.lessEditor.setValue($scope.customLess, -1);
                    }
                }
            });

            $('.vars-values-list').on('click', 'input', function(e) {
                e.stopPropagation();

                //hide picker if clicked not on color input
                if ( ! $scope.isColor(e.currentTarget.value)) {
                    return pickerCont.addClass('hidden');
                }

                var target = $(e.currentTarget),
                    offset = target.offset(),
                    width  = target.width(),
                    height = target.height();

                //save this vars name so we know what to apply colors chosen via picker
                $scope.activeVar = target.data('name');

                $('#bootstrap-theme-creator .sp-container').css({
                    top: offset.top - height + 10,
                    left: offset.left + 15,
                }).removeClass('hidden').show();
            });

            //on variable group name click set that group to actve one
            list.on('click', 'li', function(e) {
                var name = e.currentTarget.dataset.name;

                $scope.$apply(function() {
                    $scope.bootstrap.activeGroup = name;
                    $scope.bootstrap.activeVars = $scope.bootstrap.currentTheme[name];
                });
            });

            //on variables group change scroll preview iframe to that group
            $scope.$watch('bootstrap.activeGroup', function(name, oldName) {
                if (name && name != oldName) {
                    var offset = $scope.doc.find('#'+name.toLowerCase().trim().replace(/ /g, '-')).offset();

                    if (offset) {
                        $scope.doc.find('html, body').animate({ scrollTop: offset.top-20 });
                    }

                }
            });
        }
    };
}]);