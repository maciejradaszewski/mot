module.exports = function(grunt) {
    grunt.config('uglify', {
        enforcement: {
            options: {
                sourceMap: false,
                compress: {
                    drop_console: true
                }
            },
            files: {
                './mot-static/compiled/js/enforcement.<%= buildId %>.min.js': [
                    './mot-static/compiled/js/enforcement.<%= buildId %>.js'
                ]
            }
        }
    });
};