<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

use Zend\Http\Request as HttpRequest;
use Zend\Mvc\Application;

date_default_timezone_set("UTC");

// This makes our life easier when dealing with paths. Everything is relative to the application root now.
chdir(dirname(__DIR__));

mb_internal_encoding("UTF-8");
mb_http_output("UTF-8");

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require __DIR__ . '/../vendor/autoload.php';

// Run the application!
$app = Application::init(require 'config/application.config.php');
if ($app->getRequest() instanceof HttpRequest) {
    $app->getServiceManager()->get('Response')->getHeaders()->addHeaderLine('X-Frame-Options', 'DENY');
}
$app->run();
