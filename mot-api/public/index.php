<?php
/**
 * this line should be removed once BJSS configure our servers properly
 */
date_default_timezone_set("UTC");

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

mb_internal_encoding("UTF-8");
mb_http_output("UTF-8");

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloadings
require 'init_autoloader.php';

// Do not remove the following line, start-coverage.sh will modify it
#$coverageEnabled=true;

if (isset($coverageEnabled) && $coverageEnabled) {
    set_time_limit(300);

    $filter = new PHP_CodeCoverage_Filter();
    $filter->addDirectoryToWhitelist("./module");
    $filter->addDirectoryToWhitelist("../mot-common-web-module/src");
    $coverage = new PHP_CodeCoverage(null, $filter);
    $coverage->start('whatevs');
}

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();

if (isset($coverageEnabled) && $coverageEnabled) {
    // stop coverage
    $coverage->stop();

    $cov = serialize($coverage); //serialize object to disk

    file_put_contents(tempnam('/tmp/mot-api-coverage/', 'mot-api-coverage') . '.cov', $cov);
}
