module.exports = function(grunt) {
    grunt.config('autoprefixer', {
        options: {

        },
        single_file: {
        	map: false,
            src: './mot-web-frontend/public/css/app.css',
        },
    });
};