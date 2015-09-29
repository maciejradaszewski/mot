<?php

namespace TestSupport\Controller;

use TestSupport\Service\VM9913UserService;

/**
 * Creates User account with VM-9913-USER role for use by tests.
 *
 * Should not be deployed in production.
 */
class VM9913UserDataController extends BaseTestSupportRestfulController
{
    public function create($data)
    {
        /** @var $service VM9913UserService  */
        $service = $this->getServiceLocator()->get(VM9913UserService::class);
        $resultJson = $service->create($data);
        return $resultJson;
    }
}