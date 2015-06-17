/**
 * Add our db tasks into grunt
 *
 * @param grunt
 * @param config
 */
module.exports = function(grunt, config) {

    if (config.environment === config.ENV_DEVELOPMENT) {
        grunt.registerTask('env:devtools:install', 'Install the DevTools files into the Workspace directory', 'shell:install_devtools');
    } else if (config.environment === config.ENV_PRODUCTION) {
        // load our production specific db processing here (NO sshexex commands in production!!!)
    }
};