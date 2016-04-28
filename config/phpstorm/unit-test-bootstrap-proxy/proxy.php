<?php

$workspaceDir = __DIR__ . '/../../../';
$workingDir = getcwd();

if(strstr($workingDir, 'mot-web-frontend')) require_once $workspaceDir . 'mot-web-frontend/test/init.php';
if(strstr($workingDir, 'mot-common-web-module')) require_once $workspaceDir . 'mot-common-web-module/test/init.php';
if(strstr($workingDir, 'mot-api')) require_once $workspaceDir . 'mot-api/test/init.php';
