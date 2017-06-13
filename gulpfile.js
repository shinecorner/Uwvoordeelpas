var gulp = require('gulp');
var streamqueue = require('streamqueue');
var minify = require('gulp-minifier');
var concat = require('gulp-concat');
var notify = require('gulp-notify');
var sass = require('gulp-sass');

// Fonts
gulp.task('fonts', function() {
    return gulp.src('resources/assets/fonts/**/*')
        .pipe(gulp.dest('public/fonts'))
    ;
});

// Styles
gulp.task('css', function() {
    return gulp.src('resources/assets/css/{core,plugins}/**/*.css')
        .pipe(concat('app.css'))
        .pipe(gulp.dest('public/css'))
        .pipe(notify({ message: 'Styles task complete' }))
    ;
});

gulp.task('sass', function() {
    return gulp.src('resources/assets/sass/**/*.scss')
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(gulp.dest('public/css'))
        .pipe(notify({ message: 'Sass task complete' }))
    ;
});

// Scripts
gulp.task('js', function() {
    return streamqueue({ objectMode: true },
        gulp.src('resources/assets/js/core/jquery.js'),
        gulp.src('resources/assets/js/plugins/moment.js'),
        gulp.src('resources/assets/js/plugins/picker.js'),
        gulp.src('resources/assets/js/plugins/picker.time.js'),
        gulp.src('resources/assets/js/plugins/picker.date.js'),
        gulp.src('resources/assets/js/plugins/picker.dutch.js'),
        gulp.src('resources/assets/js/plugins/**/*.js'),
        gulp.src('resources/assets/js/core/javascript.js'),
        gulp.src('resources/assets/js/core/ajax.js'),
        gulp.src('resources/assets/js/core/ajax/*.js')
    )    
    .pipe(concat('app.js'))
    .pipe(gulp.dest('public/js'))
    .pipe(notify({ message: 'Scripts task complete' }))
  ;
});

gulp.task('minify', function() {
    return gulp.src('public/**/**/*').pipe(minify({
    		minify: true,
    		collapseWhitespace: true,
    		conservativeCollapse: true,
    		minifyJS: true,
    		minifyCSS: true,
    		getKeptComment: function (content, filePath) {
    			var m = content.match(/\/\*![\s\S]*?\*\//img);
    			return m && m.join('\n') + '\n' || '';
    		}
	   }))
 		 .pipe(gulp.dest('public'))
 	;
});

gulp.task('watch', function() {
  	gulp.watch('resources/assets/fonts', { interval: 750 }, [ 'fonts' ]);
    gulp.watch('resources/assets/css/{core,plugins}/**/*.css', { interval: 750 }, [ 'css' ]);
  	gulp.watch('resources/assets/sass/**/*.scss', { interval: 750 }, [ 'sass' ]);
  	gulp.watch('resources/assets/js/{core,plugins}/**/**/*.js', { interval: 750 }, [ 'js' ]);
});

gulp.task('default', ['css', 'sass', 'js', 'fonts']);
