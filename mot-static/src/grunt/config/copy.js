module.exports = function(grunt, config) {
    grunt.config('copy', {
        make_build_versions: {
            files: [
                {
                    expand: true,
                    src: '*.js',
                    dest: 'mot-static/compiled/js/',
                    cwd: 'mot-static/compiled/js/',
                    rename: function(dest, src) {
                        if (src.slice(-7) === '.min.js') {
                            return dest + src.replace(/\.min\.js$/, ".<%= buildId %>.min.js");
                        } else if (src.slice(-10) === '.min.gz.js') {
                            return dest + src.replace(/\.min\.gz\.js$/, ".<%= buildId %>.min.gz.js");
                        }else if (src.slice(-3) === '.js') {
                            return dest + src.replace(/\.js$/, ".<%= buildId %>.js");
                        }
                        return dest + src + '.nomatch.in.make_build_versions'
                    }
                }
            ]
        },
        ux_mocks: {
            files: [
                {
                    expand: true,
                    src: '**',
                    dest: 'mot-static/src/html/mocks/js',
                    cwd: 'mot-web-frontend/public/js/'
                },
                {
                    expand: true,
                    src: '**',
                    dest: 'mot-static/src/html/mocks/css',
                    cwd: 'mot-web-frontend/public/css/'
                },
                {
                    expand: true,
                    src: '**',
                    dest: 'mot-static/src/html/mocks/img',
                    cwd: 'mot-web-frontend/public/img/'
                },
		            {
			            expand: true,
			            src: 'libraries.min.js',
			            dest: 'mot-static/src/html/mocks/js/',
			            cwd: 'mot-static/compiled/js/'
		            }
            ]
        },
        css: {
            files: [
                {
                    expand: true,
                    src: 'app.css',
                    dest: 'mot-static/public/css/',
                    cwd: 'mot-web-frontend/public/css/'
                }
            ]
        },
        libraries: {
            files: [
                {
                    expand: true,
                    src: 'libraries.min.js',
                    dest: 'mot-static/src/html/mocks/js/',
                    cwd: 'mot-static/src/html/mocks/js/'
                },
                {
                    expand: true,
                    src: 'libraries.min.js',
                    dest: 'mot-static/tests/js/casper/public/js/',
                    cwd: 'mot-static/compiled/js/'
                },
                {
                    expand: true,
                    src: 'enforcement.js',
                    dest: 'mot-static/tests/js/casper/public/js/',
                    cwd: 'mot-static/compiled/js/'
                },
                {
                    expand: true,
                    src: 'libraries.<%= buildId %>.min.js',
                    dest: 'mot-web-frontend/public/js/',
                    cwd: 'mot-static/compiled/js/'
                },
                {
                    expand: true,
                    src: 'libraries.<%= buildId %>.min.gz.js',
                    dest: 'mot-web-frontend/public/js/',
                    cwd: 'mot-static/compiled/js/'
                },
                {
                    expand: true,
                    src: 'enforcement.<%= buildId %>.*',
                    dest: 'mot-web-frontend/public/js/',
                    cwd: 'mot-static/compiled/js/'
                },
                {
                    expand: true,
                    src: 'lt.ie9.<%= buildId %>.min.js',
                    dest: 'mot-web-frontend/public/js/',
                    cwd: 'mot-static/compiled/js/'
                },
                {
                    expand: true,
                    src: 'lt.ie9.<%= buildId %>.min.gz.js',
                    dest: 'mot-web-frontend/public/js/',
                    cwd: 'mot-static/compiled/js/'
                }
            ]
        },
        dvsa: {
            files: [
                {
                    expand: true,
                    src: 'dvsa.js',
                    dest: 'mot-web-frontend/public/js/',
                    cwd: 'mot-static/compiled/js/'
                },
            ]
        },
        build_files: {
            options: {
                process: function(content, path) {
                    return grunt.template.process(content);
                }
            },
            files: [
                {
                    expand: true,
                    src: 'BuildConfig.php',
                    dest: 'mot-web-frontend/module/Application/src/Application/Build/',
                    cwd: '<%= dirs.templates %>'
                },
                {
                    expand: true,
                    src: 'build-config.sh',
                    dest: './Jenkins_Scripts/',
                    cwd: '<%= dirs.templates %>'
                },
            ]
        }
    });
};