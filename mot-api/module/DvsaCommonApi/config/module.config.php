<?php

use DvsaCommon\DtoSerialization\DtoReflectiveSerializer;
use DvsaCommon\DtoSerialization\DtoReflectiveDeserializer;
use DvsaCommonApi\Factory\DtoReflectiveSerializerFactory;
use DvsaCommonApi\Factory\DtoReflectiveDeserializerFactory;

return [
    'service_manager' => [
        'abstract_factories' => [
            \DvsaCommonApi\Service\Hydrator\HydratorFactory::class,
        ],
        'factories' => [
                DtoReflectiveSerializer::class => DtoReflectiveSerializerFactory::class,
                DtoReflectiveDeserializer::class => DtoReflectiveDeserializerFactory::class
        ],
    ],
    'view_manager'    => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
