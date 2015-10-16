<?php

use DvsaClient\Factory\ExpiredPasswordMapperFactory;
use DvsaClient\Mapper\ExpiredPasswordMapper;
use DvsaClient\MapperFactory;
use DvsaClient\Factory\MapperFactory as MapperFactoryFactory;
use DvsaClient\Mapper\TesterGroupAuthorisationMapper;
use DvsaClient\Factory\TesterGroupAuthorisationMapperFactory;

return [
    'factories' => [
        MapperFactory::class => MapperFactoryFactory::class,
        TesterGroupAuthorisationMapper::class => TesterGroupAuthorisationMapperFactory::class,
        ExpiredPasswordMapper::class => ExpiredPasswordMapperFactory::class,
    ],
];