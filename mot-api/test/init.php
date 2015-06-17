<?php

include __DIR__ . '/../../mot-common-web-module/test/Bootstrap.php';

$appTestConfig = include 'test.config.php';
putenv('APPLICATION_ENV=testing');
DvsaCommonTest\Bootstrap::init($appTestConfig);