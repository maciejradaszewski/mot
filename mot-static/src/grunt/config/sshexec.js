module.exports = function(grunt, config) {
    if (config.environment === config.ENV_DEVELOPMENT) {


        function handleCoverageOptions(coverageOptions, coverageType, coveragePath) {

            coverageType = coverageType || coverageOptions.defaultCoverageType;
            if(coverageType !== 'clover' && coverageType !== 'html') {
                grunt.log.error('Invalid coverage-type parameter specified: ' + coverageType);
            }

            coveragePath = coveragePath || (
                coverageType === 'clover' ? coverageOptions.cloverPath : coverageOptions.htmlPath
            );

            var cmd = coverageOptions.baseCmd;
            if(coverageType === 'clover') {
                cmd += " --coverage-clover=" + coveragePath;
            } else if (coverageType === 'html') {
                cmd += " --coverage-html=" + coveragePath;
            }

            grunt.log.writeln('Coverage command: ' + cmd);
            return cmd;
        }

        grunt.config('sshexec', {
            options: {
                host: '<%= vagrant_config.host %>',
                username: '<%= vagrant_config.username %>',
                privateKey: '<%= vagrant_config.privateKey %>',

                coverage: {
                    api: {
                        defaultCoverageType: 'html',
                        baseCmd: 'cd /workspace/mot-api && vendor/bin/phpunit',
                        cloverPath: '/workspace/coverage/api-coverage.xml',
                        htmlPath: '/workspace/coverage/api-coverage'
                    },
                    frontend: {
                        defaultCoverageType: 'html',
                        baseCmd: 'cd /workspace/mot-web-frontend && vendor/bin/phpunit',
                        cloverPath: '/workspace/coverage/frontend-coverage.xml',
                        htmlPath: '/workspace/coverage/frontend-coverage'
                    },
                    common: {
                        defaultCoverageType: 'html',
                        baseCmd: 'cd /workspace/mot-common-web-module && vendor/bin/phpunit',
                        cloverPath: '/workspace/coverage/common-coverage.xml',
                        htmlPath: '/workspace/coverage/common-coverage'
                    }
                }
            },

            reset_database: {
                command: 'export dev_workspace="/workspace/"; cd /workspace/mot-api/db && ./reset_db_with_test_data.sh -f <%= mysql_config.user %> <%= mysql_config.password %> <%= mysql_config.host %> <%= mysql_config.database %> <%= mysql_config.grantuser %> N && echo "DB Reset"'
            },
            reset_database_no_hist: {
                command: 'export dev_workspace="/workspace/"; cd /workspace/mot-api/db && ./reset_db_with_test_data.sh -f <%= mysql_config.user %> <%= mysql_config.password %> <%= mysql_config.host %> <%= mysql_config.database %> <%= mysql_config.grantuser %> N N && echo "DB Reset without *_hist tables"'
            },
            dump_database : {
                command: 'export dev_workspace="/workspace/"; cd /workspace/mot-api/db/dev/bin && php ./dump_db.php && mysqldump -d --skip-add-drop-table -h <%= mysql_config.host %> -u <%= mysql_config.user %> -p<%= mysql_config.password %> <%= mysql_config.database %> > $dev_workspace/mot-api/db/dev/schema/create_dev_db_schema.sql && echo "DB dump"'
            },
            reset_database_full: {
                command: 'export dev_workspace="/workspace/"; cd /workspace/mot-api/db && ./reset_db_with_test_data.sh -f <%= mysql_config.user %> <%= mysql_config.password %> <%= mysql_config.host %> <%= mysql_config.database %> <%= mysql_config.grantuser %> Y && echo "DB Full Reset"'
            },
            mysql_proc_fix: {
                command: 'mysql -u<%= mysql_config.user %> -ppassword -e "use mysql; repair table mysql.proc;"'
            },
            mysql_freeze: {
                command: 'cd /workspace/mot-api/db && ./db_freeze.sh <%= mysql_config.user %> <%= mysql_config.password %> <%= mysql_config.host %> <%= mysql_config.database %> manual'
            },
            mysql_freeze_current: {
                command: 'cd /workspace/mot-api/db && ./db_freeze.sh <%= mysql_config.user %> <%= mysql_config.password %> <%= mysql_config.host %> <%= mysql_config.database %>'
            },
            mysql_thaw: {
                command: 'cd /workspace/mot-api/db && ./db_thaw.sh <%= mysql_config.user %> <%= mysql_config.password %> <%= mysql_config.host %> <%= mysql_config.database %> manual'
            },

            phpunit: {
                command: 'export dev_workspace="/workspace/"; /workspace/Jenkins_Scripts/run_unit_tests.sh'
            },
            test_php_frontend: {
                command: function() {
                    var coverageOptions = grunt.config('sshexec.options.coverage.frontend');

                    // No coverage options - bail out
                    if(!grunt.option('coverage')) {
                        return coverageOptions.baseCmd;
                    }

                    return handleCoverageOptions(
                        coverageOptions,
                        grunt.option('coverage-type'),
                        grunt.option('coverage-path')
                    );                }
            },
            test_php_api: {
                command: function() {
                    var coverageOptions = grunt.config('sshexec.options.coverage.api');

                    // No coverage options - bail out
                    if(!grunt.option('coverage')) {
                        return coverageOptions.baseCmd;
                    }

                    return handleCoverageOptions(
                        coverageOptions,
                        grunt.option('coverage-type'),
                        grunt.option('coverage-path')
                    );
                }
            },
            test_php_common: {
                command: function() {
                    var coverageOptions = grunt.config('sshexec.options.coverage.common');

                    // No coverage options - bail out
                    if(!grunt.option('coverage')) {
                        return coverageOptions.baseCmd;
                    }

                    return handleCoverageOptions(
                        coverageOptions,
                        grunt.option('coverage-type'),
                        grunt.option('coverage-path')
                    );
                }
            },
            test_behat: {
                command: function() {
                    var cmd = 'cd /workspace/mot-behat && bin/behat';

                    if(grunt.option('feature')) {
                        cmd += ' "' + grunt.option('feature') + '"';
                    }
                    if(grunt.option('format')) {
                        cmd += ' --format="' + grunt.option('format') +  '"'
                    }
                    if(grunt.option('tags')) {
                        cmd += ' --tags="' + grunt.option('tags') +  '"'
                    }

                    return cmd + ' -vv';
                }
            },
            xdebug_disable: {
                command: 'sudo sed -i.bak "s/^\\s*zend_ext/;zend_ext/g" /etc/php.d/xdebug.ini'
            },
            xdebug_enable: {
                command: 'sudo sed -i.bak "s/;\\s*zend_ext/zend_ext/g" /etc/php.d/xdebug.ini'
            },
            xdebug_on: {
                command: 'sudo sed -i.bak "s/remote_autostart=0/remote_autostart=1/g" /etc/php.d/xdebug.ini;sudo sed -i.bak "s/remote_enable=0/remote_enable=1/g" /etc/php.d/xdebug.ini; '
            },
            xdebug_off: {
                command: 'sudo sed -i.bak "s/remote_autostart=1/remote_autostart=0/g" /etc/php.d/xdebug.ini;sudo sed -i.bak "s/remote_enable=1/remote_enable=0/g" /etc/php.d/xdebug.ini; '
            },
            xhprof_disable: {
                command: '/workspace/mot-devtools/bin/xhprof.sh disable_xhprof'
            },
            xhprof_enable: {
                command: '/workspace/mot-devtools/bin/xhprof.sh enable_xhprof'
            },
            apache_restart: {
                command: 'sudo service httpd restart'
            },
            server_mod_prod: {
                command: ['sudo sed -i.bak "s/.*opcache.validate_timestamps=.*/opcache.validate_timestamps=0/g" /etc/php.d/opcache.ini']
            },
            server_mod_dev: {
                command: ['sudo sed -i.bak "s/^opcache.validate_timestamps.*/;opcache.validate_timestamps=0/g" /etc/php.d/opcache.ini']
            },
            trace_api_log: {
                command: 'tail -f /var/log/dvsa/mot-api.log'
            },
            trace_web_log: {
                command: 'tail -f /var/log/dvsa/mot-web-frontend.log'
            },
            doctrine_proxy_gen: {
                command: '/workspace/Jenkins_Scripts/generate-proxies.sh <%= grunt.option("output") || "" %>'
            },
            doctrine_default_develop_dist: {
                command: 'rm -f /workspace/mot-api/config/autoload/optimised.development.php'
            },
            doctrine_optimised_develop_dist: {
                command: 'cp /workspace/mot-api/config/autoload/optimised.development.php.dist.opt /workspace/mot-api/config/autoload/optimised.development.php'
            },
            fitnesse_suite: {
                command: 'cd /workspace/mot-fitnesse;./run_ci.sh "FrontPage.SuiteAcceptanceTests?suite" text'
            },
            fitnesse_enforcement: {
                command: 'cd /workspace/mot-fitnesse;./run_ci.sh "FrontPage.SuiteAcceptanceTests.EnforcementSuite?suite" text'
            },
            fitnesse_licensing: {
                command: 'cd /workspace/mot-fitnesse;./run_ci.sh "FrontPage.SuiteAcceptanceTests.LicensingSuite?suite" text'
            },
            fitnesse_testing: {
                command: 'cd /workspace/mot-fitnesse;./run_ci.sh "FrontPage.SuiteAcceptanceTests.TestingSuite?suite" text'
            },
            fitnesse_event: {
                command: 'cd /workspace/mot-fitnesse;./run_ci.sh "FrontPage.SuiteAcceptanceTests.EventSuite?suite" text'
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
                command: 'cd /workspace/mot-api/vendor/dvsa/dvsa-logger && ./bin/create_db.sh'
            },
            enable_dvsa_logger_api: {
                command: 'cp /workspace/mot-api/config/autoload/z.dvsalogger.development.php.dist.opt /workspace/mot-api/config/autoload/z.dvsalogger.development.php'
            },
            enable_dvsa_logger_web: {
                command: 'cp /workspace/mot-web-frontend/config/autoload/z.dvsalogger.development.php.dist.opt /workspace/mot-web-frontend/config/autoload/z.dvsalogger.development.php'
            },
            disable_dvsa_logger_api: {
                command: 'rm -f /workspace/mot-api/config/autoload/z.dvsalogger.development.php'
            },
            disable_dvsa_logger_web: {
                command: 'rm -f /workspace/mot-web-frontend/config/autoload/z.dvsalogger.development.php'
            },
            delete_doctrine_cache_folders: {
                command: 'cd /workspace/mot-api/data/ && rm -fr DoctrineModule && rm -fr DoctrineORMModule'
            }
        });
    }
};
