angular.module('builder.editors', [])

.controller('CodeEditorController', ['$scope', 'codeEditors', 'dom', 'project', function($scope, codeEditors, dom, project) {

    $scope.editors = codeEditors;

    //available themes
    $scope.themes = ['chrome', 'clouds', 'crimson_editor', 'tomorrow_night', 'dawn', 
    'dreamweaver', 'eclipse', 'github', 'solarized_light', 'textmate', 'tomorrow', 
    'xcode', 'kuroir', 'katzen_milch', 'ambiance', 'chaos', 'clouds_midnight', 'cobalt',
    'idle_fingers', 'kr_theme', 'merbivore', 'merbivore_soft', 'mono_industrial', 'monokai',
    'pastel_on_dark', 'solarized_light', 'terminal', 'tomorrow_night_blue', 'tomorrow_night_bright',
    'tomorrow_night_80s', 'twilight', 'vibrant_ink'];

    $scope.$on('builder.page.changed', function() {
        if (codeEditors.cache.htmlEditor) {
            codeEditors.ignoreHtmlEditorChange = true;
            codeEditors.cache.htmlEditor.setValue(style_html(dom.getHtml()), -1);
            codeEditors.reloadCss();
            codeEditors.cache.jsEditor.setValue(project.activePage.js, -1);
        }   
    });

    //select node html in the code editor when new node
    //is selected in the builder
    $scope.$on('element.reselected', function(e, node) {
        codeEditors.cache.htmlEditor.find(node.outerHTML);
    });

    $scope.$on('builder.css.changed', function(e) {
        if ( ! codeEditors.loadingCss) {
            codeEditors.reloadCss();
        }
    });
}])

.factory('codeEditors', ['$rootScope', 'css', 'dom', 'project', 'grid', function($rootScope, css, dom, project, grid) {

    var editors = {
        currentlyOpen: false,

        loadingCss: false,

        ignoreHtmlEditorChange: false,

        theme: 'tomorrow_night',

        cache: {},

        resizeEditors: function() {
            this.cache.htmlEditor.resize();
            this.cache.cssEditor.resize();
            this.cache.jsEditor.resize();
        },

        open: function(name) {
            this.currentlyOpen = name;

            if (name == 'css') {
                this.reloadCss();
            }

            this.cache.editors.addClass('hidden');
            $('#'+name+'-code-editor').removeClass('hidden');
            this.cache.wrapper.addClass('open');

            setTimeout(function() {
                editors.resizeEditors();
                editors.cache[name+'Editor'].focus();
            }, 300);
        },

        changeTheme: function() {
            this.cache.htmlEditor.setTheme('ace/theme/'+this.theme);
            this.cache.cssEditor.setTheme('ace/theme/'+this.theme);
            this.cache.jsEditor.setTheme('ace/theme/'+this.theme);
        },

        reloadCss: function() {
            this.loadingCss = true;
            var styles = css.compile();

            if (styles && styles !== 'undefined') {
                this.cache.cssEditor.setValue(css.compile(), -1);
            }

            this.loadingCss = false;
        },

        init: function() {
            this.cache.wrapper = $('#code-editor-wrapper');
            this.cache.editors = $('#html-code-editor, #css-code-editor, #js-code-editor');

            this.cache.htmlEditor = ace.edit('html-code-editor');
            this.cache.cssEditor = ace.edit('css-code-editor');
            this.cache.jsEditor = ace.edit('jscript-code-editor');

            setTimeout(function() {
                editors.initHtmlEditor();
                editors.initCssEditor();
                editors.initJsEditor();
            }, 2000);
        },

        initHtmlEditor: function() {
            this.cache.htmlEditor.setTheme('ace/theme/'+this.theme);
            this.cache.htmlEditor.getSession().setMode('ace/mode/html');

            //load current iframe html into the code editor
            this.cache.htmlEditor.setValue(style_html(dom.getHtml()), -1);

            $rootScope.$on('builder.html.changed', function() {
                editors.ignoreHtmlEditorChange = true;
                editors.cache.htmlEditor.setValue(style_html(dom.getHtml()), -1);
            });

            this.cache.htmlEditor.on('focus', function(e) {
                grid.hideEditor();
            });
                       
            //replace current iframe document contents with the 
            //ones from ace editor on editor content change
            editors.cache.htmlEditor.getSession().on('change', blDebounce(function() {

                if (editors.ignoreHtmlEditorChange) { 
                    return editors.ignoreHtmlEditorChange = false;
                }

                dom.loadHtml(editors.cache.htmlEditor.getValue());

            }, 200));
        },

        initCssEditor: function() {
            this.cache.cssEditor.setTheme('ace/theme/'+this.theme);
            this.cache.cssEditor.getSession().setMode('ace/mode/css');

            this.cache.cssEditor.getSession().on('change', function(e) {
                if ( ! editors.loadingCss) {
                    blDebounce(function() {
                        editors.loadingCss = true;
                        $rootScope.customCss.html(editors.cache.cssEditor.getValue());
                        $rootScope.repositionBox('select');
                        $rootScope.$broadcast('builder.css.changed');
                        editors.loadingCss = false;
                    }, 400)();
                }
            });
        },

        initJsEditor: function() {
            this.cache.jsEditor.setTheme('ace/theme/'+this.theme);
            this.cache.jsEditor.getSession().setMode('ace/mode/javascript');
            this.cache.jsEditor.setValue(project.activePage.js, -1);

            this.cache.jsEditor.getSession().on('change', function(e) {            
                blDebounce(function() {           
                    project.activePage.js = editors.cache.jsEditor.getValue();
                    $rootScope.$broadcast('builder.js.changed');                 
                }, 400)();
            });
        },

        dispose: function() {
            this.cache = {};
        }
    };

    return editors;

}])

.directive('blShowEditor', ['codeEditors', 'settings', function(codeEditors, settings) {
    return {
        restrict: 'A',
        link: function($scope, el, attrs) {
            if (settings.get('openCodeEditorByDefault')) {
                setTimeout(function() {
                   codeEditors.open('html');
                }, 2000);
            }

            el.on('click', function(e) {
                $scope.$apply(function() {
                    codeEditors.open(attrs.blShowEditor);
                })
            });
        }
    };
}])

.directive('blRenderEditors', ['codeEditors', function(codeEditors) {
    return {
   		restrict: 'A',
      	link: function($scope, el) {
            var wrapper = $('#code-editor-wrapper'),
                header  = wrapper.find('#editor-header'),
                oldPosition = {
                    top: '',
                    bottom: 20,
                    left: 380
                };

            codeEditors.init();
               
            el.find('.close-editor').on('click', function() {
                wrapper.toggle();

                $scope.$apply(function() {
                    codeEditors.currentlyOpen = false;
                });
            });

            el.find('.expand-editor').on('click', function() {
                if (wrapper.hasClass('expanded')) {
                    wrapper.css(oldPosition).removeClass('expanded');
                } else {
                    oldPosition = {
                        height: wrapper.height(),
                        width: wrapper.width(),
                        top: wrapper.offset().top,
                        left: wrapper.offset().left
                    };

                    wrapper.css({
                        height: 'calc(100% - 40px)',
                        width: 'calc(100% - 40px)',
                        top: 20,
                        left: 20
                    }).addClass('expanded');
                }
                
                //make sure css animations are done before resizing
                setTimeout(function() {
                    codeEditors.resizeEditors();
                }, 300);
            });

            //make editor panel draggable
            wrapper.draggable({
                handle: '#editor-header',
                containment: 'body'
            }).resizable({
                containment: 'body',
                minWidth: 400,
                minHeight: 200,
                start: function() {
                    $('#code-editor-resize-overlay').show();
                },
                stop: function() {
                    $('#code-editor-resize-overlay').hide();
                    codeEditors.resizeEditors();
                }
            });
      	}
    };
}]);