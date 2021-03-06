module.exports = function (grunt, config) {
        function handleCoverageOptions(coverageOptions, coverageType, coveragePath) {

            coverageType = coverageType || coverageOptions.defaultCoverageType;
            if (coverageType !== 'clover' && coverageType !== 'html') {
                grunt.log.error('Invalid coverage-type parameter specified: ' + coverageType);
            }

            coveragePath = coveragePath || (
                    coverageType === 'clover' ? coverageOptions.cloverPath : coverageOptions.htmlPath
                );

            var cmd = coverageOptions.baseCmd;
            if (coverageType === 'clover') {
                cmd += " --coverage-clover=" + coveragePath;
            } else if (coverageType === 'html') {
                cmd += " --coverage-html=" + coveragePath;
            }

            grunt.log.writeln('Coverage command: ' + cmd);
            return cmd;
        }

        function toggleFeature(inConfig, toggleState) {
            var util = require('util');
            var configFile = '<%= vagrant_config.motConfigDir %>/' + inConfig;
            var toggle = grunt.option('ft');
            return util.format(
                'sudo grep %s %s && sudo perl -pi -e "s/.*%s.*/\'%s\' => %s,/g" %s',
                toggle, configFile, toggle, toggle, toggleState, configFile
            );
        }

        function featureToggleEnable(config) {
            return toggleFeature(config, 'true');
        }

        function featureToggleDisable(config) {
            return toggleFeature(config, 'false');
        }

        function restartService(serviceName) {
            return 'sudo service ' + serviceName + ' restart';
        }

        var dev_ssh_options = {
            host: '<%= dev_config.host %>',
            username: '<%= dev_config.username %>',
            privateKey: '<%= dev_config.privateKey %>',
            port: '<%= dev_config.port %>'
        };

        var dev2_ssh_options = {
            host: '<%= dev2_config.host %>',
            username: '<%= dev2_config.username %>',
            privateKey: '<%= dev2_config.privateKey %>',
            port: '<%= dev2_config.port %>'
        };

        var php_extension_enable = 'sudo sed -i.bak "s/^.*zend_ext/zend_ext/g" <%= vagrant_config.phpConfigDir %>/php.d/';
        var php_extension_disable = 'sudo sed -i.bak "s/.*zend_ext/;zend_ext/g" <%= vagrant_config.phpConfigDir %>/php.d/';
        var opcache_ini_file = '10-opcache.ini';
        var xdebug_ini_file = '15-xdebug.ini';
        var testsupportConfigFile = 'mot-testsupport/global.php';
        var frontendConfigFile = 'mot-web-frontend/global.php';
        var apiConfigFile = 'mot-api/global.php';

        var trace_command = 'sudo tail -n 40 -f ';
        var trace_file_var_log = trace_command + '<%= vagrant_config.logDir %>';
        var trace_file_opt_dvsa = trace_command + '<%= vagrant_config.motAppDir %>';
        var exportAppConfigLocation = 'export APPLICATION_CONFIG_PATH="/opt/dvsa/mot-api/config/autoload"';

        function resetDb(dataset) {
            return [
                'export dev_workspace="<%= vagrant_config.workspace %>"',
                'cd <%= vagrant_config.workspace %>/mot-api/db && ' +
                'sudo ./reset_db_with_test_data.sh ' +
                    '<%= mysql_config.user %> ' +
                    '<%= mysql_config.password %> ' +
                    '<%= mysql_config.host %> ' +
                    '<%= mysql_config.grantuser %> ' +
                    dataset
            ];
        }

        function apacheClearPhpSessionsDev() {
            return 'sudo service <%= service_config.httpdServiceName %> stop; sudo rm -f <%= vagrant_config.phpRootDir %>/var/lib/php/session/sess_*; sudo service <%= service_config.httpdServiceName %> start;';
        }

        function papplyDev() {
            return [
                'sudo cp /tmp/hiera/hiera.yaml /etc/puppetlabs/code/hiera.yaml',
                'sudo /vagrant/scripts/papply'
            ];
        }

        function testPhp(configKey) {
            return function () {
                var coverageOptions = grunt.config(configKey);

                // No coverage options - bail out
                if (!grunt.option('coverage')) {
                    return coverageOptions.baseCmd;
                }

                return handleCoverageOptions(
                    coverageOptions,
                    grunt.option('coverage-type'),
                    grunt.option('coverage-path')
                );
            }
        }

        function xdebugOn() {
           return xdebugSwitch(1);
        }

        function xdebugOff() {
            return xdebugSwitch(0);
        }

        function xdebugSwitch(newValue) {
            var oldValue = newValue == 0 ? 1 : 0;
            return xdebugSed('remote_autostart', oldValue, newValue) + ';'
                 + xdebugSed('remote_enable',    oldValue, newValue);
        }

        function xdebugSed(key, oldValue, newValue) {
            return 'sudo sed -i.bak "s/' + key + '=' + oldValue + '/' + key + '=' + newValue + '/g" <%= vagrant_config.phpConfigDir %>/php.d/' + xdebug_ini_file;
        }

    grunt.config('sshexec', {
            options: {
                host: dev2_ssh_options.host,
                port: dev2_ssh_options.port,
                username: dev2_ssh_options.username,
                privateKey: dev2_ssh_options.privateKey,

                coverage: {
                    api: {
                        defaultCoverageType: 'html',
                        baseCmd: 'cd <%= vagrant_config.workspace %>/mot-api && vendor/bin/phpunit',
                        cloverPath: '<%= vagrant_config.workspace %>/coverage/api-coverage.xml',
                        htmlPath: '<%= vagrant_config.workspace %>/coverage/api-coverage'
                    },
                    db_verification: {
                        defaultCoverageType: 'html',
                        baseCmd: 'cd <%= vagrant_config.workspace %>/mot-api && vendor/bin/phpunit db-verification-test-suite',
                        cloverPath: '<%= vagrant_config.workspace %>/coverage/api-coverage.xml',
                        htmlPath: '<%= vagrant_config.workspace %>/coverage/api-coverage'
                    },
                    frontend: {
                        defaultCoverageType: 'html',
                        baseCmd: 'cd <%= vagrant_config.workspace %>/mot-web-frontend && vendor/bin/phpunit',
                        cloverPath: '<%= vagrant_config.workspace %>/coverage/frontend-coverage.xml',
                        htmlPath: '<%= vagrant_config.workspace %>/coverage/frontend-coverage'
                    },
                    common: {
                        defaultCoverageType: 'html',
                        baseCmd: 'cd <%= vagrant_config.workspace %>/mot-common-web-module && vendor/bin/phpunit',
                        cloverPath: '<%= vagrant_config.workspace %>/coverage/common-coverage.xml',
                        htmlPath: '<%= vagrant_config.workspace %>/coverage/common-coverage'
                    }
                }
            },

            apache_clear_php_sessions_dev: {
                options: dev_ssh_options,
                command: apacheClearPhpSessionsDev()
            },
            apache_clear_php_sessions_dev2: {
                options: dev2_ssh_options,
                command: apacheClearPhpSessionsDev()
            },
            apache_restart: {
                command: restartService("<%= service_config.httpdServiceName %>")
            },
            apache_restart_dev: {
                options: dev_ssh_options,
                command: restartService("<%= service_config.httpdServiceName %>")
            },
            apache_restart_dev2: {
                options: dev2_ssh_options,
                command: restartService("<%= service_config.httpdServiceName %>")
            },
            authorisation_service_restart: {
                options: dev2_ssh_options,
                command: restartService("<%= service_config.authrServiceName %>")
            },
            mot_test_restart: {
                options: dev2_ssh_options,
                command: restartService("<%= service_config.motTestServiceName %>")
            },
            vehicle_service_restart: {
                options: dev2_ssh_options,
                command: restartService("<%= service_config.vehicleServiceName %>")
            },
            opendj_restart_dev: {
                options: dev_ssh_options,
                command: restartService("<%= service_config.opendjServiceName %>")
            },
            jasper_restart: {
                options: dev_ssh_options,
                command: restartService("<%= service_config.jasperServiceName %>")
            },
            openam_restart: {
                options: dev2_ssh_options,
                command: restartService("<%= service_config.jasperServiceName %>")
            },
            mysql_restart_dev: {
                options: dev_ssh_options,
                command: restartService("<%= service_config.mysqlServiceName %>")
            },
            papply_dev: {
                options: dev_ssh_options,
                command: papplyDev()
            },
            papply_dev2: {
                options: dev2_ssh_options,
                command: papplyDev()
            },
            ft_enable_testsupport: {
                options: dev_ssh_options,
                command: featureToggleEnable(testsupportConfigFile)
            },
            ft_disable_testsupport: {
                options: dev_ssh_options,
                command: featureToggleDisable(testsupportConfigFile)
            },
            ft_enable_frontend: {
                options: dev_ssh_options,
                command: featureToggleEnable(frontendConfigFile)
            },
            ft_disable_frontend: {
                options: dev_ssh_options,
                command: featureToggleDisable(frontendConfigFile)
            },
            ft_enable_api: {
                options: dev2_ssh_options,
                command: featureToggleEnable(apiConfigFile)
            },
            ft_disable_api: {
                options: dev2_ssh_options,
                command: featureToggleDisable(apiConfigFile)
            },
            reset_database: {
                options: dev_ssh_options,
                command: function () {
                    return resetDb("synthetic");
                }
            },
            mysql_proc_fix: {
                options: dev_ssh_options,
                command: function () {
                    return 'sudo mysql -u<%= mysql_config.user %> -ppassword -e "use mysql; repair table mysql.proc;"';
                }
            },

            phpunit: {
                command: 'export dev_workspace="<%= vagrant_config.workspace %>"; <%= vagrant_config.workspace %>/Jenkins_Scripts/run_unit_tests.sh'
            },
            test_php_frontend: {
                command: testPhp('sshexec.options.coverage.frontend')
            },
            test_php_api: {
                command: testPhp('sshexec.options.coverage.api')
            },
            test_php_api_db_verification: {
                command: testPhp('sshexec.options.coverage.db_verification')
            },
            test_php_common: {
                command: testPhp('sshexec.options.coverage.common')
            },
            test_behat: {
                command: function () {
                    var cmd = 'cd <%= vagrant_config.workspace %>/mot-behat && bin/behat';

                    if (grunt.option('feature')) {
                        cmd += ' "' + grunt.option('feature') + '"';
                    }
                    if (grunt.option('format')) {
                        cmd += ' --format="' + grunt.option('format') + '"'
                    }
                    if (grunt.option('tags')) {
                        cmd += ' --tags="' + grunt.option('tags') + '"'
                    }

                    return cmd + ' -vv';
                }
            },
            mysql_general_log_disable: {
                options: dev_ssh_options,
                command: 'sudo sed -i.bak "s/^general-log = 1/general-log = 0/g" <%= vagrant_config.mysqlConfigDir %>/etc/my.cnf'
            },
            mysql_general_log_enable: {
                options: dev_ssh_options,
                command: 'sudo sed -i.bak "s/^general-log = 0/general-log = 1/g" <%= vagrant_config.mysqlConfigDir %>/etc/my.cnf'
            },
            opcache_unload_dev: {
                options: dev_ssh_options,
                command: php_extension_disable + opcache_ini_file
            },
            opcache_unload_dev2: {
                options: dev2_ssh_options,
                command: php_extension_disable + opcache_ini_file
            },
            opcache_load_dev: {
                options: dev_ssh_options,
                command: php_extension_enable + opcache_ini_file
            },
            opcache_load_dev2: {
                options: dev2_ssh_options,
                command: php_extension_enable + opcache_ini_file
            },
            xdebug_disable_dev: {
                options: dev_ssh_options,
                command: php_extension_disable + xdebug_ini_file
            },
            xdebug_disable_dev2: {
                options: dev2_ssh_options,
                command: php_extension_disable + xdebug_ini_file
            },
            xdebug_enable_dev: {
                options: dev_ssh_options,
                command: php_extension_enable + xdebug_ini_file
            },
            xdebug_enable_dev2: {
                options: dev2_ssh_options,
                command: php_extension_enable + xdebug_ini_file
            },
            xdebug_on_dev: {
                options: dev_ssh_options,
                command: xdebugOn()
            },
            xdebug_on_dev2: {
                options: dev2_ssh_options,
                command: xdebugOn()
            },
            xdebug_off_dev: {
                options: dev_ssh_options,
                command: xdebugOff()
            },
            xdebug_off_dev2: {
                options: dev2_ssh_options,
                command: xdebugOff()
            },
            ft2fa_off_dev: {
                options: dev_ssh_options,
                command: 'sudo chmod 777 /etc/dvsa/mot-web-frontend/global.php; sudo sed -i.bak "s|.*2fa.enabled.*true|\'2fa.enabled\' => false|g" /etc/dvsa/mot-web-frontend/global.php'
            },
            ft2fa_on_dev: {
                options: dev_ssh_options,
                command: 'sudo chmod 777 /etc/dvsa/mot-web-frontend/global.php; sudo sed -i.bak "s|.*2fa.enabled.*false|\'2fa.enabled\' => true|g" /etc/dvsa/mot-web-frontend/global.php'
            },
            ft2fa_off_dev2: {
                options: dev2_ssh_options,
                command: [
                    'sudo chmod 777 /etc/dvsa/mot-api/global.php; sudo sed -i.bak "s|.*2fa.enabled.*true|\'2fa.enabled\' => false|g" /etc/dvsa/mot-api/global.php',
                    'sudo sed -i.bak "s|twoFactorEnabled.*true|twoFactorEnabled: false|g" /etc/dvsa/authorisation-service/config.yml'
                    ]
            },
            ft2fa_on_dev2: {
                options: dev2_ssh_options,
                command: [
                    'sudo chmod 777 /etc/dvsa/mot-api/global.php; sudo sed -i.bak "s|.*2fa.enabled.*false|\'2fa.enabled\' => true|g" /etc/dvsa/mot-api/global.php',
                    'sudo sed -i.bak "s|twoFactorEnabled.*false|twoFactorEnabled: true|g" /etc/dvsa/authorisation-service/config.yml'
                ]
            },
            ft2fahardstop_off_dev: {
                options: dev_ssh_options,
                command: 'sudo chmod 777 /etc/dvsa/mot-web-frontend/global.php; sudo sed -i.bak "s|.*2fa.hardstop.enabled.*true|\'2fa.hardstop.enabled\' => false|g" /etc/dvsa/mot-web-frontend/global.php'
            },
            ft2fahardstop_on_dev: {
                options: dev_ssh_options,
                command: 'sudo chmod 777 /etc/dvsa/mot-web-frontend/global.php; sudo sed -i.bak "s|.*2fa.hardstop.enabled.*false|\'2fa.hardstop.enabled\' => true|g" /etc/dvsa/mot-web-frontend/global.php'
            },
            xhprof_disable: {
                command: '<%= vagrant_config.workspace %>/mot-devtools/bin/xhprof.sh disable_xhprof'
            },
            xhprof_enable: {
                command: '<%= vagrant_config.workspace %>/mot-devtools/bin/xhprof.sh enable_xhprof'
            },
            server_mod_prod: {
                command: ['sudo sed -i.bak "s/.*opcache.validate_timestamps=.*/opcache.validate_timestamps=0/g" <%= vagrant_config.phpConfigDir %>/php.d/' + opcache_ini_file]
            },
            server_mod_dev: {
                command: ['sudo sed -i.bak "s/^opcache.validate_timestamps.*/;opcache.validate_timestamps=0/g" <%= vagrant_config.phpConfigDir %>/php.d/' + opcache_ini_file]
            },
            trace_api_log: {
                options: dev2_ssh_options,
                command: trace_file_var_log + '/dvsa/mot-api.log'
            },
            trace_web_log: {
                options: dev_ssh_options,
                command: trace_file_var_log + '/dvsa/mot-webfrontend.log'
            },
            trace_mysql_log: {
                options: dev_ssh_options,
                command: trace_file_var_log + '/mysql/general.log'
            },
            trace_api_access_log: {
                options: dev2_ssh_options,
                command: trace_file_var_log + '/httpd/mot-api_access.log'
            },
            trace_frontend_access_log: {
                options: dev_ssh_options,
                command: trace_file_var_log + '/httpd/dev.motdev.org.uk_ssl_access_ssl.log'
            },
            trace_api_error_log: {
                options: dev2_ssh_options,
                command: trace_file_var_log + '/httpd/mot-api_error.log'
            },
            trace_frontend_error_log: {
                options: dev_ssh_options,
                command: trace_file_var_log + '/httpd/dev.motdev.org.uk_ssl_error_ssl.log'
            },
            trace_api_system_log: {
                options: dev2_ssh_options,
                command: trace_file_var_log + '/messages'
            },
            trace_frontend_system_log: {
                options: dev_ssh_options,
                command: trace_file_var_log + '/messages'
            },
            trace_testsupport_access_log: {
                options: dev_ssh_options,
                command: trace_file_var_log + '/httpd/mot-testsupport_access.log'
            },
            trace_testsupport_error_log: {
                options: dev_ssh_options,
                command: trace_file_var_log + '/httpd/mot-testsupport_error.log'
            },
            trace_jasper_access_log: {
                options: dev_ssh_options,
                command: function(){
                    return trace_file_var_log + '/tomcat/localhost_access_log.' + new Date().toISOString().substr(0, 10) + '.txt'
                }
            },
            trace_vehicle_service_access_log: {
                options: dev2_ssh_options,
                command: trace_file_opt_dvsa + '/vehicle-service/log/access.log'
            },
            trace_vehicle_service_error_log: {
                options: dev2_ssh_options,
                command: trace_file_opt_dvsa + '/vehicle-service/log/vehicle-service.log'
            },
            password_policy_show: {
                command: 'cd /etc/openam/opends/bin/ && sudo ./dsconfig  get-password-policy-prop  \
                    --hostname localhost  \
                    --port 4444  \
                    --bindDN "cn=Directory Manager" \
                    --bindPassword cangetinam \
                    --policy-name "DVSA MOT Users Policy" \
                    --trustAll'
            },
            password_policy_list: {
                command: 'cd /etc/openam/opends/bin/ && sudo ./dsconfig  list-password-policies  \
                    --hostname localhost  \
                    --port 4444  \
                    --bindDN "cn=Directory Manager" \
                    --bindPassword cangetinam \
                    --trustAll'
            },
            password_policy_delete: {
                command: 'cd /etc/openam/opends/bin/ && sudo ./dsconfig delete-password-policy  \
                    --hostname localhost  \
                    --port 4444  \
                    --bindDN "cn=Directory Manager" \
                    --bindPassword cangetinam \
                    --policy-name "DVSA MOT Users Policy" \
                    --no-prompt \
                    --trustAll \
                    && sudo ./dsconfig delete-password-validator  \
                    --hostname localhost  \
                    --port 4444  \
                    --bindDN "cn=Directory Manager" \
                    --bindPassword cangetinam \
                    --validator-name "DVSA MOT Password Validator" \
                    --no-prompt \
                    --trustAll \
                    && sudo ./dsconfig delete-password-validator  \
                    --hostname localhost  \
                    --port 4444  \
                    --bindDN "cn=Directory Manager" \
                    --bindPassword cangetinam \
                    --validator-name "DVSA MOT Password Character Set Validator" \
                    --no-prompt \
                    --trustAll'
            },
            password_policy_create: {
                command: 'cd /etc/openam/opends/bin/ && sudo ./dsconfig create-password-policy \
                    --set default-password-storage-scheme:salted\\ SHA-512 \
                    --set password-expiration-warning-interval:0d \
                    --set password-attribute:userpassword \
                    --set force-change-on-reset:true \
                    --set max-password-age:90d \
                    --set password-history-count:24 \
                    --set expire-passwords-without-warning:true \
                    --policy-name DVSA\\ MOT\\ Users\\ Policy \
                    --hostname localhost \
                    --trustAll --port 4444 \
                    --bindDN cn=Directory\\ Manager \
                    --bindPassword cangetinam \
                    --no-prompt \
                    --type password-policy \
                    && sudo ./dsconfig  create-password-validator  \
                    --hostname localhost  \
                    --port 4444  \
                    --bindDN "cn=Directory Manager" \
                    --bindPassword cangetinam \
                    --validator-name "DVSA MOT Password Validator" \
                    --set min-password-length:8 \
                    --set enabled:true \
                    --type length-based --no-prompt --trustAll\
                    && sudo ./dsconfig  create-password-validator  \
                    --hostname localhost  \
                    --port 4444  \
                    --bindDN "cn=Directory Manager"  \
                    --bindPassword cangetinam  \
                    --validator-name "DVSA MOT Password Character Set Validator" \
                    --set allow-unclassified-characters:false \
                    --set enabled:true  \
                    --set character-set:0:abcdefghijklmnopqrstuvwxyz \
                    --set character-set:0:ABCDEFGHIJKLMNOPQRSTUVWXYZ  \
                    --set character-set:0:0123456789  \
                    --set character-set:0:\\!\\?\\-\\_\\(\\)\\:\\=\\" \
                    --set min-character-sets:3 \
                    --type character-set \
                    --no-prompt \
                    --trustAll \
                    && sudo ./dsconfig set-password-policy-prop \
                    --hostname localhost \
                    --port 4444 \
                    --bindDN "cn=Directory Manager" \
                    --bindPassword cangetinam \
                    --policy-name "DVSA MOT Users Policy" \
                    --set password-validator:"DVSA MOT Password Character Set Validator" \
                    --set password-validator:"DVSA MOT Password Validator" \
                    --no-prompt \
                    --trustAll \
                    && printf "dn: cn=MOT Password Policy,dc=mot,dc=gov,dc=uk \
                    changetype: add \
                    add: ds-pwp-password-policy-dn \
                    objectclass: collectiveAttributeSubEntry \
                    objectclass: extensibleObject \
                    objectclass: subentry \
                    objectclass: top \
                    ds-pwp-password-policy-dn;collective: cn=DVSA MOT Users Policy,cn=Password Policies,cn=config \
                    subtreeSpecification: { specificationFilter \\"(objectclass=motUser)\\" }" > /tmp/passwordpolicy \
                    sudo ./ldapmodify -h localhost -p 1389 -D"cn=directory manager" --bindPassword cangetinam -c -f /tmp/passwordpolicy && sudo rm /tmp/passwordpolicy \
                    '
            },
            openam_refresh_users_db: {
                options: dev_ssh_options,
                command: "sudo sh /vagrant/scripts/import-data.sh"
            },
            doctrine_proxy_gen: {
                command:
                    exportAppConfigLocation + ' && ' +
                    '<%= vagrant_config.workspace %>/Jenkins_Scripts/generate-proxies.sh'
            },
            doctrine_default_develop_dist: {
                command: 'rm -f <%= vagrant_config.workspace %>/mot-api/config/autoload/optimised.development.php'
            },
            jasper_tomcat_restart: {
                options: {
                    host: '<%= jasper_config.host %>',
                    username: '<%= jasper_config.username %>',
                    privateKey: '<%= jasper_config.privateKey %>'
                },
                command: 'sudo /opt/jasperreports/ctlscript.sh restart'
            },
            create_dvsa_logger_db: {
                command: 'cd <%= vagrant_config.workspace %>/mot-api/vendor/dvsa/dvsa-logger && ./bin/create_db.sh'
            },
            zend_dev_tools_enable: {
                options: dev_ssh_options,
                command: [
                    'sed "s/.*dummy_key.*//g" <%= vagrant_config.workspace %>/config/zend-framework/mot-web-frontend/zenddevelopertools.development.php > /tmp/zenddevelopertools.development.php',
                    'sudo cp /tmp/zenddevelopertools.development.php <%= vagrant_config.motConfigDir %>/mot-web-frontend/zenddevelopertools.development.php',
                    'rm -f /tmp/zenddevelopertools.development.php'
                ]
            },
            zend_dev_tools_disable: {
                options: dev_ssh_options,
                command: 'sudo rm -f <%= vagrant_config.motConfigDir %>/mot-web-frontend/zenddevelopertools.development.php'
            },
            delete_doctrine_cache_folders: {
                command: 'cd <%= vagrant_config.workspace %>/mot-api/data/ && rm -fr DoctrineModule && rm -fr DoctrineORMModule'
            }
        });
};
