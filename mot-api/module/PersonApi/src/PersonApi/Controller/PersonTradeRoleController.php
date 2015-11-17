<?php

namespace PersonApi\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use PersonApi\Service\PersonTradeRoleService;

class PersonTradeRoleController extends AbstractDvsaRestfulController
{
    protected $personTradeRoleService;

    public function __construct(PersonTradeRoleService $personTradeRoleService)
    {
        $this->personTradeRoleService = $personTradeRoleService;
    }

    public function get($personId)
    {
        $dto = $this->personTradeRoleService->getForPerson($personId);

        return $this->returnDto($dto);
    }
}
