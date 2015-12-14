module.exports = function(grunt, config) {
    grunt.config('mysql_config', {
        user: config.legacy === false ? 'motdbuser' : 'root',
        password: 'password',
        host: 'localhost',
        database: 'mot',
        grantuser: 'motdbuser'
    });
};