var gulp = require('gulp');
var autoprefixer = require('gulp-autoprefixer');
var sass = require('gulp-sass');
var cleanCSS = require('gulp-clean-css');
var rename = require('gulp-rename');

var fieldPath = 'src/assets/field/dist';

gulp.task('field-css', function() {
    return gulp.src(fieldPath+'/css/tinymce-field.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(cleanCSS())
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest(fieldPath+'/css'));
});

gulp.task('field', ['field-css']);
gulp.task('default', ['field']);
