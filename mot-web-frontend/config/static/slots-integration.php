<?php

use DvsaClient\MapperFactory;
use DvsaCommon\HttpRestJson\Client;
use SlotPurchase\Constant\ServiceAlias;

return [
    'service_manager' => [
        'aliases' => [
            ServiceAlias::MOT_MAPPER_FACTORY        => MapperFactory::class,
            ServiceAlias::MOT_API_CLIENT            => Client::class,
            ServiceAlias::MOT_IDENTITY_PROVIDER     => 'MotIdentityProvider',
            ServiceAlias::MOT_AUTHORISATION_SERVICE => 'AuthorisationService',
        ],
    ],
];
