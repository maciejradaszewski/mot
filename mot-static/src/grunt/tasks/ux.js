/**
 * Add our ux tasks into grunt
 *
 * @param grunt
 * @param config
 */
module.exports = function(grunt, config) {
    if (config.environment === config.ENV_DEVELOPMENT) {
        grunt.registerTask('ux:mocks', 'Host the UX Html Mocks', [
            'express:ux_mocks:stop',
            'clean:ux_mocks',
            'copy:ux_mocks',
            'folder_list:ux_mocks',
            'express:ux_mocks'
        ]);
        grunt.registerTask('styleguide:serve', 'Host the Styleguide', [
            'express:styleguide:stop',
            'express:styleguide'
        ]);
    }
};
