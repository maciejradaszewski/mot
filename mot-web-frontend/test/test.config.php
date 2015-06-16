<?php
$config = [
    'modules' => [
        'Account',
        'Application',
        'Core',
        'Csrf',
        'Dashboard',
        'DvsaAuthentication',
        'DvsaFeature',
        'DvsaCommon',
        'DvsaClient',
        'Event',
        'Organisation',
        'Session',
        'Site',
        'UserAdmin',
        'Vehicle',
        'ZendDeveloperTools',
        'MaglMarkdown',
        'Soflomo\Purifier',
        'Report',
    ],
];

$config['test_namespaces'] = [
    'AccountTest' => realpath(__DIR__ . '/../module/Account/test/AccountTest'),
    'ApplicationTest' => realpath(__DIR__ . '/../module/Application/test/ApplicationTest'),
    'CoreTest' => realpath(__DIR__ . '/../module/Core/test/CoreTest'),
    'CsrfTest' => realpath(__DIR__ . '/../module/Csrf/test/CsrfTest'),
    'DashboardTest' => realpath(__DIR__ . '/../module/Dashboard/test/DashboardTest'),
    'DvsaAuthenticationTest' => realpath(__DIR__ . '/../module/DvsaClient/test/DvsaAuthenticationTest'),
    'DvsaClientTest' => realpath(__DIR__ . '/../module/DvsaClient/test/DvsaClientTest'),
    'DvsaMotEnforcementTest' => realpath(__DIR__ . '/../module/Application/test/DvsaMotEnforcementTest'),
    'DvsaMotEnforcementApiTest' => realpath(__DIR__ . '/../module/Application/test/DvsaMotEnforcementApiTest'),
    'DvsaMotTestTest' => realpath(__DIR__ . '/../module/Application/test/DvsaMotTestTest'),
    'EventTest' => realpath(__DIR__ . '/../module/Event/test/EventTest'),
    'OrganisationTest' => realpath(__DIR__ . '/../module/Organisation/test/OrganisationTest'),
    'SessionTest' => realpath(__DIR__ . '/../module/Session/test/SessionTest'),
    'Site' => realpath(__DIR__ . '/../module/Site/test/SiteTest'),
    'UserAdminTest' => realpath(__DIR__ . '/../module/UserAdmin/test/UserAdminTest'),
    'ReportTest' => realpath(__DIR__ . '/../module/Report/test/ReportTest'),
];

return $config;