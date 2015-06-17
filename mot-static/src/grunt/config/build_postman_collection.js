module.exports = function(grunt) {
    grunt.config('build_postman_collection', {
        default_options: {
            options: {
                src: './tests/postman/',
                collectionName: 'DVSA Test Runner',
                collectionDescription: 'Test the DVSA Application APIs',
                collectionFilePath: './tests/postman/collection.json',
                environmentFilePath: './tests/postman/environment.json',
                resultsFilePath: './tests/postman/run-results.json'
            }
        }
    });
};