<?php
date_default_timezone_set("UTC");

require_once 'SplClassLoader.php';
$classLoader = new SplClassLoader();
$classLoader->register();

// Composer autoloading
$composerAutoloaderPath = dirname(dirname(__DIR__)) . '/vendor/autoload.php';
if (file_exists($composerAutoloaderPath)) {
    $loader = include $composerAutoloaderPath;
}
