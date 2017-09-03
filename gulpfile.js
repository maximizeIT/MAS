var gulp = require('gulp'),
    php = require('gulp-connect-php'),
    browserSync = require('browser-sync');

var reload  = browserSync.reload;

gulp.task('php', function() {
    php.server({ base: 'App', port: 9000, keepalive: true});
});
gulp.task('browser-sync',['php'], function() {
    browserSync({
    	proxy: 'localhost:9000',
        port: 9000,
        open: true,
        notify: false
    });
});
gulp.task('default', ['browser-sync'], function () {
    gulp.watch(['App/**/*.php'], [reload]);
});