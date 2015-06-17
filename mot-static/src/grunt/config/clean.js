module.exports = function (grunt) {
    grunt.config('clean', {
        build: [
            './mot-static/public/css/app.css',
            './mot-static/public/css/style-guide.css',
            './mot-static/public/css/style-guide.min.css',
            '<%= dirs.frontend.css %>app.css',
            '<%= dirs.frontend.js %>enforcement*',
            '<%= dirs.frontend.js %>libraries*',
            '<%= dirs.frontend.js %>lt.ie9*',
            '<%= dirs.frontend.js %>build-config.json',
            './mot-static/compiled/js/*',
            './mot-static/tests/js/casper/public/js/*'
        ],
        release: [
            './mot-static/public/css/app.css',
            './mot-static/public/css/style-guide.css',
            './mot-static/public/css/style-guide.min.css',
            '<%= dirs.frontend.css %>app.css',
            '<%= dirs.frontend.js %>enforcement.*',
            '<%= dirs.frontend.js %>libraries.*',
            '<%= dirs.frontend.js %>lt.ie9.*',
            '<%= dirs.frontend.js %>build-config.json',
            './mot-static/compiled/js/*',
            './mot-static/tests/js/casper/public/js/*'
        ],
        ux_mocks: ['./mot-static/src/html/mocks/css',
            './mot-static/src/html/mocks/js',
            './mot-static/src/html/mocks/img'
        ]
    });
};