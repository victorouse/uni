var gulp = require('gulp');
var browserify = require('browserify');
var source = require('vinyl-source-stream');
var reactify = require('reactify');
var gutil = require('gulp-util');

var handleError = function(err) {
    gutil.beep();
    console.log(err);

    this.emit('end');
}

gulp.task('bundleHome', function() {
    var b = browserify({
        entries: [
          './client/home/home.js',
        ]
    }).transform(reactify);

    b.bundle()
        .on('error', handleError)
        .pipe(source('home.js'))
        .pipe(gulp.dest('./server/public/js/pages'))
});

gulp.task('bundleSearch', function() {
    var b = browserify({
        entries: [
          './client/search/search.js',
        ]
    }).transform(reactify);

    b.bundle()
        .on('error', handleError)
        .pipe(source('search.js'))
        .pipe(gulp.dest('./server/public/js/pages'))
});

gulp.task('bundleVideo', function() {
    var b = browserify({
        entries: [
          './client/video/video.js',
        ]
    }).transform(reactify);

    b.bundle()
        .on('error', handleError)
        .pipe(source('video.js'))
        .pipe(gulp.dest('./server/public/js/pages'))
});


gulp.task('watch', function() {
    gulp.watch('./client/home/components/**/*.js', ['bundleHome'])
    gulp.watch('./client/search/components/**/*.js', ['bundleSearch'])
    gulp.watch('./client/video/components/**/*.js', ['bundleVideo'])
})

gulp.task('default', ['bundleHome', 'bundleSearch', 'bundleVideo', 'watch']);


