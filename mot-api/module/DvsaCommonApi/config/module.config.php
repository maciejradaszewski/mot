<?php

return [
    'service_manager' => [
        'abstract_factories' => [
            \DvsaCommonApi\Service\Hydrator\HydratorFactory::class,
        ],
    ],
    'view_manager'    => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
