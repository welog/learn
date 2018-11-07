var gulp       = require('gulp'),
    uglify     = require('gulp-uglify'),
    // minifyHtml = require('gulp-minify-html'),
    // minifyCss  = require('gulp-minify-css'),
    //imagemin   = require('gulp-imagemin'),
    //pngquant   = require('imagemin-pngquant'),
    rename     = require('gulp-rename');
    // concat     = require('gulp-concat'),
    // less       = require('gulp-less'),
    // livereload = require('gulp-livereload'),
    // replace = require('gulp-replace');


gulp.task('uglifyJS', function () {
    gulp.src('src/scripts/**/*.js')
        .pipe(uglify())
        .pipe(concat('index.js'))
        .pipe(replace('src/views', 'dist/views'))
        .pipe(gulp.dest('dist/js'));
});

gulp.task('jsmin', function () {
    gulp.src('./prompt.js')
        .pipe(uglify())
        .pipe(rename('prompt.min.js'))
        .pipe(gulp.dest('./'));
});

gulp.task('compressLogin', function () {
    gulp.src('src/scripts/controllers/login.js')
        .pipe(uglify())
        .pipe(gulp.dest('src/styles'))
        .pipe(livereload());
});

gulp.task('minifyHTML', function() {
    gulp.src(['src/views/**/*.html'])
        .pipe(minifyHtml())
        //.pipe(rename('index.min.php'))
        .pipe(gulp.dest('dist/views'));
});

gulp.task('minifyCSS', function() {
    gulp.src(['src/styles/*', '!src/styles/site.css', '!src/styles/login.js'])
        .pipe(minifyCss())
        .pipe(gulp.dest('dist/css'));
});

//gulp.task('imagemin', function() {
//    return gulp.src('src/images/*')
//        .pipe(imagemin({ progressive: true,}))
//        .pipe(gulp.dest('dist/images'));
//});

gulp.task('watch', function () {
    livereload.listen();
    gulp.watch('src/scripts/*/*.js', ['compressLogin']);
});

gulp.task('default',['uglifyJS', 'minifyHTML', 'minifyCSS']);

//gulp.task(name[, deps], fn) 定义任务  name：任务名称 deps：依赖任务名称 fn：回调函数
//gulp.src(globs[, options]) 执行任务处理的文件  globs：处理的文件路径(字符串或者字符串数组)



