module.exports = function(grunt) {
    grunt.config('mysql_config', {
        user: 'root',
        password: 'password',
        host: 'localhost',
        database: 'mot',
        grantuser: 'motdbuser'
    });
};