/**
 * Add our dev tasks into grunt
 *
 * @param grunt
 * @param config
 */
module.exports = function (grunt, config) {
    if (config.environment === config.ENV_DEVELOPMENT) {
        grunt.registerTask('dev:optimise', 'Switches the environment into optimised mode',
            [
                'sshexec:reset_database',
                'sshexec:mysql_freeze_current',
                'sshexec:xdebug_disable',
                'sshexec:doctrine_optimised_develop_dist',
                'sshexec:server_mod_prod',
                'doctrine:proxy',
                'sshexec:apache_restart'
            ]
        );
        grunt.registerTask('dev:std', 'Switches the environment into standard development mode',
            [
                'sshexec:apache_restart', // reset DB requires a clean class cache, hence reset happens twice
                'sshexec:reset_database',
                'sshexec:server_mod_dev',
                'sshexec:doctrine_default_develop_dist',
                'sshexec:apache_restart'
            ]
        );
        grunt.registerTask('dev:create_dvsa_logger_db', 'Creates the DVSA Logger database',
            [
                'sshexec:create_dvsa_logger_db'
            ]
        );
        grunt.registerTask('dev:dvsa_logger_enable', 'Enables the DVSA Logger',
            [
                'sshexec:enable_dvsa_logger_api',
                'sshexec:enable_dvsa_logger_web',
                'sshexec:apache_restart'
            ]
        );
        grunt.registerTask('dev:dvsa_logger_disable', 'Disables the DVSA Logger',
            [
                'sshexec:disable_dvsa_logger_api',
                'sshexec:disable_dvsa_logger_web',
                'sshexec:apache_restart'
            ]
        );

        // ENVIRONMENT MAINTENANCE TASKS
        grunt.registerTask('env:mot:updatecheck', 'Disables the DVSA Logger', [
            'shell:env_dvsa_update_check'
        ]);
        grunt.registerTask('env:mot:hotfix', 'Disables the DVSA Logger', [
            'shell:env_dvsa_hotfix'
        ]);
    }
};