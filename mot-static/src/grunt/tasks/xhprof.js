/**
 * @param grunt
 */
module.exports = function(grunt) {
    grunt.registerTask(
        'xhprof:load',
        'Ensure xhprof profiling is enabled',
        [
            'sshexec:xhprof_enable',
            'sshexec:apache_restart'
        ]);
    grunt.registerTask(
        'xhprof:unload',
        'Ensure xhprof profiling is disabled',
        [
            'sshexec:xhprof_disable',
            'sshexec:apache_restart'
        ]);
};
