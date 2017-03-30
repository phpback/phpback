//--------------//
// DEPENDENCIES //
//--------------//

var gulp = require('gulp');
var del = require('del');
var runSequence = require('run-sequence');
var compass = require('gulp-compass');
var rename = require("gulp-rename");
var minifyCSS = require('gulp-minify-css');

//---------------//
// CONFIGURATION //
//---------------//

var cssDir = './css';
var scssDir = './scss';
var miniSuffix = '.min';

//-------//
// TASKS //
//-------//

gulp.task('default', function (callback) {
  runSequence('clean', 'build', callback);
});

gulp.task('clean', function (callback) {
  del([cssDir + '/*'], callback);
});

gulp.task('build', ['build:styles']);

gulp.task('build:styles', function () {

  var compassConfig = {
    css: cssDir,
    sass: scssDir,
    environment: 'development',
    style: 'expanded',
    comments: true
  };

  return gulp.src(scssDir + '/*.scss')

  // Writing development version.
    .pipe(compass(compassConfig))
    .pipe(gulp.dest(cssDir))

    // Writing minified version.
    .pipe(rename({suffix: miniSuffix}))
    .pipe(minifyCSS())
    .pipe(gulp.dest(cssDir))
    ;
});
