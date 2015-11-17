<?php

use DvsaCommon\DtoSerialization\DtoReflectiveSerializer;
use DvsaCommonApi\Factory\DtoReflectiveSerializerFactory;

return [
    'service_manager' => [
        'abstract_factories' => [
            \DvsaCommonApi\Service\Hydrator\HydratorFactory::class,
        ],
        'factories' => [
                DtoReflectiveSerializer::class => DtoReflectiveSerializerFactory::class,
        ],
    ],
    'view_manager'    => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
