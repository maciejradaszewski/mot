module.exports = function(grunt, config) {

    if(config.environment === config.ENV_DEVELOPMENT) {
    
        var execSync  = require('exec-sync');
        var util      = require('util');
        var infFolder = process.env['dev_workspace'] + '/../infrastructure';

        // Wrapper: If a VM is unresponsive, answers "" instead of blowing out grunt.
        var getKeyCmd = function(cmd) {
            var file = execSync(util.format(cmd, infFolder));
            return (file.length > 0) ? grunt.file.read(file) : '';
        };

        grunt.config('vagrant_config', {
            host:       '192.168.149.30',
            username:   'vagrant',
            privateKey: getKeyCmd('cd %s && vagrant ssh-config lamp-mot | grep IdentityFile | awk \'{print $2}\'')
        });

        grunt.config('jasper_config', {
            host:       '192.168.149.33',
            username:   'vagrant',
            privateKey: getKeyCmd('cd %s && vagrant ssh-config jasper2 | grep IdentityFile | awk \'{print $2}\'')
        });
    }
};
