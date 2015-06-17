module.exports = function(grunt) {
    grunt.config('jshint', {
        all: ['src/*.js',
            'src/**/*.js',
            'tests/**/*.js',
            '!tests/**/test.js',
            'mot-static/src/**/*.js'
        ],
        options: {
            curly: true,
            eqeqeq: true,
            immed: true,
            noarg: true,
            sub: true,
            undef: true,
            unused: 'false',
            boss: true,
            eqnull: true,
            trailing: true,
            node: true
        }
    });
};