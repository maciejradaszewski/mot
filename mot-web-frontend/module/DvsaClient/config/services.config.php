<?php

use DvsaClient\MapperFactory;
use DvsaClient\Factory\MapperFactory as MapperFactoryFactory;

return [
    'factories' => [
        MapperFactory::class => MapperFactoryFactory::class
    ],
];