/**
 * Add our lint tasks into grunt
 *
 * @param grunt
 * @param config
 */
module.exports = function(grunt, config) {
    grunt.registerTask('lint', 'Lint all relevant files', ['lint:js']);
    grunt.registerTask('lint:js', 'Lint all relevant JavaScript files', 'jshint');
};