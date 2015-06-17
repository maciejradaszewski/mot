module.exports = function(grunt) {
    grunt.config('express', {
        casper: {
            options: {
                script: 'mot-static/tests/js/casper/server.js',
                    background: true,
                    debug: true
            }
        },
        casper_perm: {
            options: {
                script: 'mot-static/tests/js/casper/server.js',
                    background: false
            }
        },
        ux_mocks: {
            options: {
                script: 'mot-static/src/html/mocks-server.js',
                    background: false
            }
        },
        styleguide: {
            options: {
                script: 'mot-static/public/styleguide.js',
                    background: false
            }
        }
    });
};
