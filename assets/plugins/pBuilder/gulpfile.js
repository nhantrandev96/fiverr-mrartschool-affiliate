// Include gulp
var gulp = require('gulp'); 

// Include Our Plugins
var jshint = require('gulp-jshint');
var less   = require('gulp-less');
var minifyCSS = require('gulp-minify-css');
var path = require('path');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var sourcemaps = require('gulp-sourcemaps');
var browserSync = require('browser-sync');
var autoprefixer = require('gulp-autoprefixer');
var reload      = browserSync.reload;

gulp.task('less', function () {
  gulp.src('./source/less/builder.less')
  	//.pipe(sourcemaps.init())
    .pipe(less())
    .on('error', function (err) {
    	this.emit('end');
    })
   	.pipe(autoprefixer({
        browsers: ['last 2 versions'],
        cascade: false,
        remove: false
    }))
    //.pipe(sourcemaps.write())
    .pipe(minifyCSS())
    .pipe(gulp.dest('./public/css'))
    .pipe(browserSync.reload({stream:true}));
});

gulp.task('iframeLess', function () {
  gulp.src('./source/less/iframe.less')
    .pipe(less())
    .pipe(minifyCSS())
    .pipe(gulp.dest('./public/css'))
    .pipe(browserSync.reload({stream:true}));
});

gulp.task('lint', function() {
    return gulp.src('js/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

gulp.task('scripts', function() {
    return gulp.src([
    	'source/js/vendor/beautify-html.js',
		'source/js/vendor/jquery.js',
		'source/js/builder/utils/stringHelpers.js',
		'source/js/vendor/jquery-ui.js',
		'source/js/vendor/resizable.js',
		'source/js/vendor/html2canvas.min.js',
		'source/js/vendor/bootstrap/transition.js',
		'source/js/vendor/bootstrap/collapse.js',
		'source/js/vendor/bootstrap/modal.js',
		'source/js/vendor/bootstrap/dropdown.js',
		'source/js/vendor/bootstrap/alert.js',
		'source/js/vendor/bootstrap/tooltip.js',
		'source/js/vendor/pagination.js',
		'source/js/vendor/jquery.mCustomScrollbar.js',
		'source/js/vendor/jquery.mousewheel.js',
		'source/js/vendor/toggles.js',
		'source/js/vendor/alertify.js',
		'source/js/vendor/rangy/rangy-core.js',
		'source/js/vendor/rangy/rangy-cssclassapplier.js',
		'source/js/vendor/spectrum.js',
		'source/js/vendor/knob.js',
		'source/js/vendor/zero-clip.min.js',
		'source/js/vendor/angular.min.js',
		'source/js/vendor/angular-animate.min.js',
		'source/js/vendor/angular-translate.js',
		'source/js/vendor/angular-translate-url-loader.js',
		'source/js/vendor/flow.js',
		'source/js/builder/styling/fonts.js',
		'source/js/builder/dragAndDrop/draggable.js',
		'source/js/builder/dragAndDrop/iframeScroller.js',
		'source/js/builder/dragAndDrop/resizable.js',
		'source/js/builder/dragAndDrop/grid.js',
		'source/js/builder/resources/icons.js',
		'source/js/builder/resources/colors.js',
		'source/js/builder/editors/wysiwyg.js',
		'source/js/builder/elements/definitions/bootstrap.js',
		'source/js/builder/elements/definitions/base.js',
		'source/js/builder/elements/panel.js',
		'source/js/builder/elements/repository.js',
		'source/js/builder/inspector/inspector.js',
		'source/js/builder/inspector/attributes.js',
		'source/js/builder/inspector/border.js',
		'source/js/builder/inspector/marginPadding.js',
		'source/js/builder/inspector/text.js',
		'source/js/builder/inspector/shadows.js',
		'source/js/builder/inspector/actions.js',
		'source/js/builder/inspector/background/background.js',
		'source/js/builder/inspector/background/mediaManagerController.js',
		'source/js/builder/settings.js',
		'source/js/builder/directives.js',
		'source/js/builder/app.js',
		'source/js/builder/controllers/navbarController.js',
		'source/js/builder/controllers/linkerController.js',
		'source/js/builder/context/contextBoxes.js',
		'source/js/builder/undoManager.js',
		'source/js/builder/dom.js',
		'source/js/builder/context/contextMenu.js',
		'source/js/builder/dragAndDrop/iframeDragAndDropWidget.js',
		'source/js/builder/dragAndDrop/columnsResizeWidget.js',
		'source/js/builder/editors/codeEditor.js',
		'source/js/builder/editors/libraries.js',
		'source/js/builder/styling/themes.js',
		'source/js/builder/styling/templates.js',
		'source/js/builder/styling/themesCreator.js',
		'source/js/builder/styling/css.js',
		'source/js/builder/utils/localStorage.js',
		'source/js/builder/editors/imageEditor.js',
		'source/js/builder/projects/project.js',
		'source/js/builder/projects/pagesController.js',
		'source/js/builder/projects/export.js',
		'source/js/builder/keybinds.js',
		'source/js/builder/dashboard/template.js',
		'source/js/builder/translator.js',
		'source/js/builder/**/**.js'
	])
    .pipe(concat('builder.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('public/js'))
    .pipe(browserSync.reload({stream:true}));
});

// Watch Files For Changes
gulp.task('watch', function() {
	browserSync({
        proxy: "localhost/architect-lite/"
    });

    gulp.watch('source/js/**/*.js', ['scripts']);
    gulp.watch('source/less/iframe.less', ['iframeLess']);
    gulp.watch('source/less/**/*.less', ['less']);
    gulp.watch('views/*.html').on('change', reload);
});

// Default Task
gulp.task('default', ['less', 'scripts', 'watch']);