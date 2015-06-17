/**
 * Add our test tasks into grunt
 *
 * @param grunt
 * @param config
 */
module.exports = function(grunt, config) {

    grunt.registerTask('build', 'Build our system', ['clean:build', 'build:js', 'build:css', 'copy:build_files', 'build:composer']);
    grunt.registerTask('build:js', 'Build the JavaScript files', ['concat', 'copy:make_build_versions', 'uglify', 'copy:libraries']);
    grunt.registerTask('build:css', 'Build the css files then automagically vendor prefix', ['sass', 'autoprefixer', 'copy:css']);
    grunt.registerTask('build:composer', 'Download all the composer dependencies for each module', ['shell:composer']);
    grunt.registerTask('build:config-reload', 'Modifies config files (replace by .dist scripts)', ['shell:config_reload']);

    if (config.environment === config.ENV_DEVELOPMENT) {
        grunt.registerTask('build:autoload_classmaps', 'Build the Classmap files for the API and Frontend', ['sshexec:autoload_classmaps']);
    }
};