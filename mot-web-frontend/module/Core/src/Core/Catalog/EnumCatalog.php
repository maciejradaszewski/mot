<?php

namespace Core\Catalog;

use Application\Service\CatalogService;
use Core\Catalog\BusinessRole\BusinessRoleCatalog;
use DvsaCommon\Utility\Lazy;

class EnumCatalog
{
    private $businessRoleCatalog;

    public function __construct(CatalogService $catalog)
    {
        $this->businessRoleCatalog = new Lazy(function () use ($catalog) {
            return new BusinessRoleCatalog($catalog);
        });
    }

    /**
     * @return BusinessRoleCatalog
     */
    public function businessRole()
    {
        return $this->businessRoleCatalog->value();
    }
}
