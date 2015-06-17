module.exports = function(grunt) {
    grunt.config('folder_list', {
        ux_mocks: {
            files: [{
                src: ['*.html'],
                dest: 'mot-static/src/html/mocks/mocks.json',
                cwd: 'mot-static/src/html/mocks/'
            }]
        }
    });
};