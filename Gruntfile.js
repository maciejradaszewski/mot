/**
 * This is our main project Gruntfile. it has been split up into multiple
 * files to make maintenance easier.
 *
 * All config and task files found in the config and task directories are loaded
 * into the main grunt config file at run time. Both are provided with an environment
 * variable which can be used to dynamically load environment aware tasks.
 *
 * This is important because the VM (sshexec) tasks must not be used in jenkins.
 *
 *
 * Directories:
 * ------------
 * mot-static/src/grunt/configs
 * mot-static/src/grunt/tasks
 *
 * Environments:
 * ------------
 * display the development tasks
 * grunt --help
 *
 * display the production tasks
 * grunt --help --env=production
 *
 * use the --env flag or set the bash variable GRUNT_ENV to "production"
 * to get the production version of grunt tasks.
 *
 * @param grunt
 */
module.exports = function(grunt) {

	require('load-grunt-tasks')(grunt);
    require('nopt-grunt-fix')(grunt)

    var mot = require('./mot-static/src/grunt/mot-grunt.js');

    grunt.initConfig({
        pkg:                grunt.file.readJSON('package.json'),
        buildId:            grunt.template.today("yyyymmddhhmmss"),
        buildDateTime:      grunt.template.today("dddd, mmmm dS, yyyy, h:MM:ss TT"),
        ENV_DEVELOPMENT:    'development',
        ENV_PRODUCTION:     'production',
        environment:        grunt.option('env') || process.env.GRUNT_ENV || 'development',
        legacy:             grunt.option('legacy') || false
    });

    mot.initialiseGrunt(grunt);
};
