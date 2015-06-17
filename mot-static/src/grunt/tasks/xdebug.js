/**
 * Add our test tasks into grunt
 *
 * @param grunt
 */
module.exports = function(grunt, config) {
    if (config.environment === config.ENV_DEVELOPMENT) {
    	/**
    	 * XBDEBUG :: LOAD
    	 *
    	 * This will cause the .so file to be loaded so that PHPStorm can
    	 * be used in debug mode.
    	 */
        grunt.registerTask(
        	'xdebug:load',
        	'Ensure xdebug extension is loaded at runtime', 
        	[
        		'sshexec:xdebug_enable',  // ensure the .so file is uncommented
        		'sshexec:apache_restart'  // restart apache to prime
        	]);
    	/**
    	 * XBDEBUG :: UNLOAD
    	 *
    	 * Completely disables any xdebug runtime loading. This ensures maximum
    	 * performance of PHP wrt. to xdebug having CPU timer.
    	 */
        grunt.registerTask(
        	'xdebug:unload',
        	'Prevent xdebug extension being loaded',
        	[
        		'sshexec:xdebug_disable',
        		'sshexec:apache_restart'
        	]);
    	/**
    	 * XBDEBUG :: ON
    	 *
    	 * Makes sure that XDebug can cause breakpoints to happen.
    	 */
        grunt.registerTask(
        	'xdebug:on',
        	'Enables xdebug for a debugging session',
        	[
        		'sshexec:xdebug_on',
        		'sshexec:apache_restart'
        	]);
    	/**
    	 * XBDEBUG :: OFF
    	 *
    	 * Makes sure that XDebug does not trigger breakpoints.
    	 */
        grunt.registerTask(
        	'xdebug:off',
        	'Stops xdebug listening for a debugger',
        	[
        		'sshexec:xdebug_off',
        		'sshexec:apache_restart'
        	]);
    }
};
