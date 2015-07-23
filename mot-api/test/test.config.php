<?php
$config = [
    'modules' => [
        //'DvsaCommon',
        'AccountApi',
        'CensorApi',
        'DataCatalogApi',
        'DoctrineModule',
        'DoctrineORMModule',
        'DvsaDoctrineModule',
        'DvsaAuthentication',
        'DvsaAuthorisation',
        'DvsaDocument',
        'DvsaCommonApi',
        'DvsaElasticSearch',
        'DvsaEntities',
        'DvsaEventApi',
        'DvsaMotApi',
        'EquipmentApi',
        'IntegrationApi',
        'OrganisationApi',
        'NonWorkingDaysApi',
        'NotificationApi',
        'SiteApi',
        'UserApi',
        'UserFacade',
        'VehicleApi',
        'MailerApi',
        'DvsaApplicationLogger'
    ],
];

$config['test_namespaces'] = [
    'DvsaCommonApiTest' => __DIR__ . '/../module/DvsaCommonApi/test/DvsaCommonApiTest',
    'CensorApiTest' => __DIR__ . '/../module/CensorApi/test/CensorApiTest',
    'DataCatalogApi' => __DIR__ . '/../module/DataCatalogApi/test/DataCatalogApiTest',
    'DvsaEntitiesTest'      => __DIR__ . '/../module/DvsaEntities/test/DvsaEntitiesTest',
    'DvsaElasticSearchTest' => __DIR__ . '/../module/DvsaElasticSearch/test/DvsaElasticSearchTest',
    'DvsaEventApiTest' => __DIR__ . '/../module/DvsaEventApi/test/DvsaEventApiTest',
    'DvsaMotApiTest'        => __DIR__ . '/../module/DvsaMotApi/test/DvsaMotApiTest',
    'IntegrationApiTest' => __DIR__  . '/../module/IntegrationApi/test/IntegrationApiTest',
    'DvlaInfoTest' => __DIR__  . '/../module/IntegrationApi/test/DvlaInfoTest',
    'OpenInterfaceTest' => __DIR__  . '/../module/IntegrationApi/test/OpenInterfaceTest',
    'TransportForLondonTest' => __DIR__  . '/../module/IntegrationApi/test/TransportForLondonTest',
    'NonWorkingDaysApiTest' => __DIR__  . '/../module/NonWorkingDaysApi/test/NonWorkingDaysApiTest',
    'NotificationApiTest' => __DIR__  . '/../module/NotificationApi/test/NotificationApiTest',
    'SiteApiTest' => __DIR__  . '/../module/SiteApi/test/SiteApiTest',
    'UserApiTest' => __DIR__  . '/../module/UserApi/test/UserApiTest',
    'MailerApiTest' => __DIR__  . '/../module/MailerApi/test/MailerApiTest'

];

return $config;
