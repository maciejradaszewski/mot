/**
 * Add our db tasks into grunt
 *
 * @param grunt
 * @param config
 */
module.exports = function(grunt, config) {

    if (config.environment === config.ENV_DEVELOPMENT) {
        grunt.registerTask('db:reset', 'Reset the database on the VM with the small data set', 'sshexec:reset_database');
        grunt.registerTask('db:reset-no-hist', 'Reset the database on the VM with the small data set, without *_hist tables and triggers', 'sshexec:reset_database_no_hist');
        grunt.registerTask('db:dump', 'Dumps the database on the VM', 'sshexec:dump_database');
        grunt.registerTask('db:full', 'Reset the database on the VM with the full sample data set', 'sshexec:reset_database_full');
        grunt.registerTask('db:repair', 'Repair a broken mysql proc table in the VM', 'sshexec:mysql_proc_fix');
        grunt.registerTask('db:freeze', 'Snapshot the mot database state to a tmp file. Previous freeze is lost!', 'sshexec:mysql_freeze');
        grunt.registerTask('db:thaw', 'Recover the mot database from the last freeze', 'sshexec:mysql_thaw');

    } else if (config.environment === config.ENV_PRODUCTION) {
        // load our production specific db processing here (NO sshexex commands in production!!!)
    }
};