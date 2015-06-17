<?php
return [
    'modules'         => [
        'DvsaFeature',
        'DoctrineModule',
        'DoctrineORMModule',
        'DvsaCommonApi',
        'NotificationApi',
        'DvsaEntities',
        'DvsaMotApi',
        'UserFacade',
        'DvsaAuthorisation',
        'DvsaAuthentication'
    ],
    'test_namespaces' => [
        'NotificationApiTest' => __DIR__ . '/' . 'NotificationApiTest',
        'DvsaCommonApiTest'   => __DIR__ . '/../../DvsaCommonApi/test/DvsaCommonApiTest',
    ]
];
