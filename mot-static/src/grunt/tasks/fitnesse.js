/**
 * Add our fitnesse tasks into grunt
 *
 * @param grunt
 * @param config
 */
module.exports = function(grunt, config) {
    if (config.environment === config.ENV_DEVELOPMENT) {
        // Fitnesse tasks - Optimise system before running.. disables xdebug
        var runFitnesseTestInOptimisedEnvironment = function (task) {
            grunt.task.run([
                'dev:optimise',
                    'sshexec:' + task
            ]);
        };

        grunt.registerTask('fitnesse:suite', 'Run fitnesse in an optimised environment', function () {
            runFitnesseTestInOptimisedEnvironment('fitnesse_suite')
        });
        grunt.registerTask('fitnesse:enforcement', 'Run the enforcement suite within fitnesse in an optimised environment', function () {
            runFitnesseTestInOptimisedEnvironment('fitnesse_enforcement')
        });
        grunt.registerTask('fitnesse:licensing', 'Run the licensing suite within fitnesse in an optimised environment', function () {
            runFitnesseTestInOptimisedEnvironment('fitnesse_licensing')
        });
        grunt.registerTask('fitnesse:testing', 'Run the testing suite within fitnesse in an optimised environment', function () {
            runFitnesseTestInOptimisedEnvironment('fitnesse_testing')
        });
        grunt.registerTask('fitnesse:event', 'Run the event suite within fitnesse in an optimised environment', function () {
            runFitnesseTestInOptimisedEnvironment('fitnesse_event')
        });
    }
};