<?php
return [
    'modules' => [
        'DoctrineModule',
        'DoctrineORMModule',
        'DvsaAuthentication',
        'DvsaAuthorisation',
        'DvsaCommonApi',
        'DvsaEntities',
        'UserFacade',
        'DvsaMotApi',
        'UserApi',
        'UserFacade',
        'AccountApi'
    ],
    'test_namespaces' => [
        'DvsaCommonApiTest' => __DIR__ . '/../../DvsaCommonApi/test/DvsaCommonApiTest',
    ]
];
