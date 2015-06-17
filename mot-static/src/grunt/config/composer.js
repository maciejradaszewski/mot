/*global module, require, __dirname */
var path = require('path');
var rootDir = path.join(__dirname, '/../../../../');

module.exports = function (grunt) {

    grunt.config('composerModules', {
        dirs: grunt.file.expand({cwd: rootDir}, '*/composer.json').map(function(dirName) {
            return path.join(rootDir, path.dirname(dirName));
        })
    });
};