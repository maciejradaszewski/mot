<?php
return [
    'modules'         => [
        'DvsaFeature',
        'DvsaCommonApi',
        'DvsaEntities',
        'DvsaMotApi',
        'UserFacade',
        'DvsaAuthentication',
        'DvsaAuthorisation',
    ],
    'test_namespaces' => [
        'DvsaCommonApiTest' => __DIR__ . '/' . 'DvsaCommonApiTest',
    ]
];
