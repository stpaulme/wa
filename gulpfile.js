var gulp    = require('gulp');
var sass    = require('gulp-sass');
var cssnano = require('gulp-cssnano');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var concat  = require('gulp-concat');

var paths = {
    styles: {
        // Where are the SCSS files?
        src: 'src/scss/*.scss',
        // Where should the compiled file go?
        dest: 'static/css'
    },
    js: {
        // Where are the JS files?
        src: [
            'node_modules/bootstrap/dist/js/bootstrap.min.js',
            'node_modules/popper.js/dist/popper.min.js',
        ],
        // Where should the JS files go?
        dest: 'static/js'
    }
}

function style() {
    return gulp.src(paths.styles.src)

        .pipe(sourcemaps.init())

            .pipe(sass()).on('error', sass.logError)
            .pipe(autoprefixer({
                overrideBrowserslist: ['last 2 versions'],
                cascade: false
            }))
            .pipe(cssnano())
            .pipe(concat('spm.css'))

        .pipe(sourcemaps.write())

    .pipe(gulp.dest(paths.styles.dest))
}
exports.style = style

function js() {
    return gulp.src(paths.js.src)
    
        .pipe(gulp.dest(paths.js.dest))
}
exports.js = js

function watch() {
    gulp.watch(paths.styles.src, style)
}
exports.watch = watch

gulp.task('default', function() {
    style()
    js()
    watch()
});