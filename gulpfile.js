var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var cleanCSS = require('gulp-clean-css');
var rename = require('gulp-rename');

var tinymcePath = 'vendor/tinymce/tinymce';
var libPath = 'lib';
var fieldPath = 'src/assets/field/dist';

gulp.task('tinymce-classic', function() {
    console.log(tinymcePath+'/tinymce.min.js')
    return gulp.src(tinymcePath+'/tinymce.min.js')
        .pipe(gulp.dest(libPath+'/tinymce/dist'));
});

gulp.task('craft-sass', function() {
    return gulp.src('node_modules/craftcms-sass/src/_mixins.scss')
        .pipe(gulp.dest('lib/craftcms-sass'));
});

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

gulp.task('tinymce', ['tinymce-classic']);
gulp.task('field', ['field-css']);
gulp.task('default', ['tinymce', 'field']);
