
/**
 * Add our userSync tasks into grunt
 *
 * @param grunt
 * @param config
 */
module.exports = function(grunt, config) {

    if (config.environment === config.ENV_DEVELOPMENT) {
        grunt.registerTask('ldap:usersync', 'Synchronize the Database to the LDAP Database for authentication', 'sshexec:user_sync');

    } else if (config.environment === config.ENV_PRODUCTION) {
        // ToDo
    }
};