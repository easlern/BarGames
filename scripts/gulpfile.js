var gulp = require('gulp')
var concat = require('gulp-concat')
var sourcemaps = require('gulp-sourcemaps')
var uglify = require('gulp-uglify')
var ngAnnotate = require('gulp-ng-annotate')

gulp.task('js', function () {
    gulp.src(['bargames/**/module.js', 'bargames/**/*.js'])
        .pipe(sourcemaps.init())
        .pipe(concat('app.js'))
        .pipe(ngAnnotate())
        .pipe(uglify({mangle: false}))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('.'))
});


gulp.task('watch', ['js'], function () {
    gulp.watch('bargames/**/*.js', ['js'])
    gulp.watch('bargames/**/module.js', ['js'])
});