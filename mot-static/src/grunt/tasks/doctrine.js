/**
 * Add our doctrine tasks into grunt
 *
 * @param grunt
 * @param config
 */
module.exports = function(grunt, config) {
    if (config.environment === config.ENV_DEVELOPMENT) {
        grunt.registerTask('doctrine:default', 'Reset the local doctrine file to default settings', ['sshexec:doctrine_default_local_dist', 'sshexec:apache_restart']);
        grunt.registerTask('doctrine:optimise', 'Reset the local doctrine file to default settings', ['sshexec:doctrine_optimised_local_dist', 'sshexec:apache_restart']);
        grunt.registerTask('doctrine:proxy', 'Generates doctrine proxies', ['sshexec:doctrine_proxy_gen']);
    }
};