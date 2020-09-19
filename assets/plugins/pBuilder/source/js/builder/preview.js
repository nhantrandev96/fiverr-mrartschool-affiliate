angular.module('builder').factory('preview', ['$rootScope', 'dom', 'project', function($rootScope, dom, project) {
    var preview = {

        iframe: $rootScope.previewFrame,

        /**
         * Show preview iframe.
         *
         * @return void
         */
        show: function() {
            var self = preview;

            if ( ! self.iframe) {
                self.iframe  = $('<iframe id="preview-frame"></iframe>').insertAfter('#viewport');
                self.doc     = self.iframe[0].contentWindow.document;
                self.builder = $('#viewport');
                self.closer  = $('#preview-closer');
            }

            self.load(dom.getHtml(null, true, true, project.activePage));

            //add base tag so we can load assets with relative paths
            $(self.doc).find('head').prepend('<base href="//">');

            //self.handlePreviews();
            self.handleLinks();

            self.iframe.removeClass('hidden');
            self.builder.css('right', -self.builder.width());
            self.closer.removeClass('hidden');
        },

        /**
         * Hide preview iframe.
         *
         * @return void
         */
        hide: function() {
            var self = this;
            self.builder.css('right', 0);

            setTimeout(function() {
                self.iframe.addClass('hidden');
                self.closer.addClass('hidden');

                if (self.doc) {
                    self.doc.open();
                    self.doc.write('');
                    self.doc.close();
                }
            }, 300);
        },

        /**
         * Load given html into preview iframe.
         *
         * @param  string html
         * @return void
         */
        load: function(html) {
            this.doc.open('text/html', 'replace');
            this.doc.write(dom.previewsToHtml(html));
            this.doc.close();
        },

        /**
         * Handle user clicks on links in preview iframe.
         *
         * @return void
         */
        handleLinks: function() {
            $(this.doc).find('a').off('click').on('click', function(e) {
                e.preventDefault();

                var href = e.currentTarget.getAttribute('href');

                //if it's a url to hash just bail
                if (href.indexOf('#') > -1)
                {
                    return;
                }

                //if it's an absolute url confirm and continue
                else if (href.indexOf('//') > -1)
                {
                    alertify.confirm('This might navigate you away from the builder, are you sure you want to continue?', function(confirmed) {
                        if (confirmed) {
                            window.location = href;
                        }
                    });
                }

                //otherwise it's a builder page and we'll need to load that
                //pages contents into preview iframe on link click
                else {
                    var pageName = href.replace('.html', '').toLowerCase();

                    for (var i = 0; i < project.active.pages.length; i++) {
                        var page = project.active.pages[i];

                        if (page.name.toLowerCase() == pageName) {
                            console.log('x');
                            preview.load(dom.getHtml(page, true, true));
                            preview.handleLinks();
                        }
                    }
                }
            });
        }

    };

    return preview;
}]);