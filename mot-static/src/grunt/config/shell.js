/**
 * Run the newman binary from the command line directly instead of going through the grunt
 * plugins, which seems to lose some of the testing capability.
 *
 * I have raised this issue with the newman team but have had no response yet.. Running Newman.execute
 * does NOT give the same impact as running the binary so we write a simple wrapper for the moment
 * to provide command line API testing of our postman collection.
 *
 *
 * @param grunt
 * @param config
 */
module.exports = function(grunt, config) {
    grunt.config('shell', {
        newman: {
            command: "./node_modules/newman/bin/newman -c ./tests/postman/collection.json -n 1 -o ./tests/postman/results.json"
        },
        jasper_remove: {
            command: "curl --user jasperadmin:jasperadmin -v -XDELETE http://jasper:8080/jasperserver/rest/resource/MOT >/dev/null 2>&1"
        },
        jasper_sync: {
            command: "$scripts_workspace/developer/jasper.sh ../jasperreports/ jasper root password localhost motdbuser password mot 1"
        },
        composer: {
            command: grunt.config.get('composerModules').dirs.map(function(dir) {
                return 'cd ' + dir + ' && composer install -o'
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
