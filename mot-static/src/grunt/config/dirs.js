module.exports = function(grunt) {
    grunt.config('dirs', {
        templates: './mot-static/src/js/build/templates/',
        frontend: {
            public: './mot-web-frontend/public/',
            css: './mot-web-frontend/public/css/',
            js: './mot-web-frontend/public/js/'
        },
        grunt: {
            tasks: './mot-static/src/grunt/tasks/'
        }
    });
};