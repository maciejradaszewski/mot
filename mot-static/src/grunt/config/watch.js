module.exports = function(grunt) {
    grunt.config('watch', {
        js: {
            options: {
                spawn: true,
                interrupt: true,
                debounceDelay: 250
            },
            files: [
                'Gruntfile.js',
                './mot-static/src/modules/**/*.js',
                './mot-static/tests/modules/**/*.js'
            ],
            tasks: ['test:js', 'build:js']
        },
        css: {
            options: {
                spawn: true,
                interrupt: true,
                debounceDelay: 250
            },
            files: ['./mot-static/src/scss/**/*.scss'],
            tasks: ['build:css']
        }
    });
};