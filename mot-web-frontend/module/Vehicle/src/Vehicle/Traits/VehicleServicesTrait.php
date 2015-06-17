<?php

namespace Vehicle\Traits;

use DvsaClient\MapperFactory;

/**
 * Class VehicleServicesTrait
 *
 * @package Vehicle\Traits
 */
trait VehicleServicesTrait
{
    /**
     * @return MapperFactory
     */
    protected function getMapperFactory()
    {
        return $this->getServiceLocator()->get(MapperFactory::class);
    }
}
