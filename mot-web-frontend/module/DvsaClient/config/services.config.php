<?php

use DvsaClient\MapperFactory;
use DvsaClient\Factory\MapperFactory as MapperFactoryFactory;
use DvsaClient\Mapper\TesterGroupAuthorisationMapper;
use DvsaClient\Factory\TesterGroupAuthorisationMapperFactory;

return [
    'factories' => [
        MapperFactory::class => MapperFactoryFactory::class,
        TesterGroupAuthorisationMapper::class => TesterGroupAuthorisationMapperFactory::class
    ],
];