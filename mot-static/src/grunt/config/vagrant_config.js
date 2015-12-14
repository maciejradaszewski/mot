module.exports = function(grunt, config) {

    if (config.environment === config.ENV_DEVELOPMENT) {

        var execSync  = require('exec-sync');
        var util      = require('util');
        var infFolder = (config.legacy !== true) ?
            process.env['dvsa_workspace'] + '/mot-vagrant' : process.env['dev_workspace'] + '/../infrastructure';

        var getSSHPort = function(vagrantHost) {
            var port = execSync(util.format('cd %s && vagrant ssh-config ' + vagrantHost + ' | grep Port | awk \'{print $2}\'', infFolder));

            return port ? parseInt(port, 10) : 22;
        };

        // Wrapper: If a VM is unresponsive, answers "" instead of blowing out grunt.
        var getKeyCmd = function(cmd) {
            var file = execSync(util.format(cmd, infFolder));

            return (file.length > 0) ? grunt.file.read(file) : '';
        };

        if (config.legacy === false) {
            grunt.config('service_config', {
                //workspace:  '/home/vagrant/mot',
                //phpRootDir: '/opt/rh/php55/root',
                httpdServiceName: 'httpd24-httpd'
            });

            // dev (Frontend, OpenDJ, Jasper, MySQL, Distauth)
            grunt.config('dev_config', {
                host:       '10.10.10.30',
                port:       22,
                username:   'vagrant',
                privateKey: getKeyCmd('cd %s && vagrant ssh-config dev | grep IdentityFile | awk \'{print $2}\''),
                workspace:  '/home/vagrant/mot'
            });
            // dev2 (API, OpenAM)
            grunt.config('dev2_config', {
                host:       '10.10.10.50',
                port:       22,
                username:   'vagrant',
                privateKey: getKeyCmd('cd %s && vagrant ssh-config dev2 | grep IdentityFile | awk \'{print $2}\''),
                workspace:  '/home/vagrant/mot',
                phpRootDir: '/opt/rh/php55/root'
            });

            // dev2 (API, OpenAM)
            grunt.config('vagrant_config', grunt.config.get('dev2_config'));
            // dev (Frontend, OpenDJ, Jasper, MySQL, Distauth)
            grunt.config('jasper_config', grunt.config.get('dev_config'));
            // dev2 (API, OpenAM)
            grunt.config('devopenam_config', grunt.config.get('dev2_config'));
        } else {
            grunt.config('service_config', {
                //workspace:  '/workspace',
                //phpRootDir: '',
                httpdServiceName: 'httpd'
            });

            grunt.config('vagrant_config', {
                host:       '192.168.149.30',
                port:       22,
                username:   'vagrant',
                privateKey: getKeyCmd('cd %s && vagrant ssh-config lamp-mot | grep IdentityFile | awk \'{print $2}\''),
                workspace:  '/workspace',
                phpRootDir: '',
            });
            grunt.config('jasper_config', {
                host:       '192.168.149.33',
                port:       22,
                username:   'vagrant',
                privateKey: getKeyCmd('cd %s && vagrant ssh-config jasper2 | grep IdentityFile | awk \'{print $2}\'')
            });
            grunt.config('devopenam_config', {
                host:       '192.168.149.9',
                port:       22,
                username:   'vagrant',
                privateKey: getKeyCmd('cd %s && vagrant ssh-config devopenam | grep IdentityFile | awk \'{print $2}\'')
            });
        }
    }
};
