/**
 * Add our ux tasks into grunt
 *
 * @param grunt
 * @param config
 */
module.exports = function(grunt, config) {
    if (config.environment === config.ENV_DEVELOPMENT) {
        grunt.registerTask('default', 'Run "all the things.."', ['test', 'lint', 'build']);

    } else if (config.environment === config.ENV_PRODUCTION) {
        grunt.registerTask('default', 'test & build the production system', ['test', 'build']);
    }
};