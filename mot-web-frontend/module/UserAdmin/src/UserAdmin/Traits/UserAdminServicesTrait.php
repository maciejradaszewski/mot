<?php

namespace UserAdmin\Traits;

use DvsaClient\MapperFactory;

trait UserAdminServicesTrait
{
    /**
     * @return MapperFactory
     */
    protected function getMapperFactory()
    {
        return $this->getServiceLocator()->get(MapperFactory::class);
    }
}
