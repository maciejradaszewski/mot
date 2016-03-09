/**
 * Add our trace tasks into grunt
 *
 * @param grunt
 * @param config
 */
module.exports = function(grunt, config) {
    if (config.environment === config.ENV_DEVELOPMENT) {
        grunt.registerTask('trace:api', 'Trace the API logs', 'sshexec:trace_api_log');
        grunt.registerTask('trace:web', 'Trace the web logs', 'sshexec:trace_web_log');
        grunt.registerTask('trace:mysql', 'Trace the mysql general log', 'sshexec:trace_mysql_log');
    }
};