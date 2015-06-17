module.exports = function (grunt) {
    grunt.config('casper',  {
        test: {
            options: {
                test: true,
                includes: [
                    'mot-static/tests/js/casper/public/js/libraries.min.js',
                    'mot-static/tests/js/casper/public/js/enforcement.js',
                    'mot-static/tests/js/casper/public/js/classes.js'
                ]
            },
            files: {
                'mot-static/tests/js/casper/casper-results.xml': ['./mot-static/tests/js/casper/enforcement/*/test*.js']
            }
        }
    });
};