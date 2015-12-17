
module.exports = function(grunt, config) {
    grunt.config('shell', {
        jasper_remove: {
            command: "curl --user jasperadmin:jasperadmin -v -XDELETE http://jasper:8080/jasperserver/rest/resource/MOT >/dev/null 2>&1"
        },
        jasper_sync: {
            command: "$scripts_workspace/developer/jasper.sh ../jasperreports/ jasper root password localhost motdbuser password mot 1"
        },
        composer: {
            command: grunt.config.get('composerModules').dirs.map(function(dir) {
                return 'cd ' + dir + ' && composer install && composer dump-autoload -o'
            }).join('; ')
        },
        install_devtools: {
            command: "./Jenkins_Scripts/install_devtools.sh"
        },
        env_dvsa_update_check: {
            command: '$scripts_workspace/env/dvsa_update_check.sh'
        },
        env_dvsa_hotfix: {
            command: '$scripts_workspace/env/dvsa_hotfix.sh'
        },
        config_reload: {
            command: './mot-static/src/scripts/reload_config_files_from_dist.sh'
        }
    });
};
