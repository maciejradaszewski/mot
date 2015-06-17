module.exports = function(grunt) {
    grunt.config('sass', {
        dist: {
            options: {
                style: 'compressed'
            },
            files: {
                './mot-web-frontend/public/css/app.css'             : './mot-static/src/scss/app.scss',
                './mot-static/public/css/pattern-library.css'       : './mot-static/src/scss/pattern-library.scss',
            }
        }
    });
};