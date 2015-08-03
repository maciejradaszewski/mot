<?php

namespace TestSupport\Controller;

use TestSupport\Service\VM10519UserService;
use TestSupport\Service\VM10619RoleManagementUpgradeService;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Creates User account with VM-10519-USER role for use by tests.
 *
 * Should not be deployed in production.
 */
class VM10619RoleManagementUpgradeController extends BaseTestSupportRestfulController
{
    public function create($data)
    {
        /** @var $service VM10619RoleManagementUpgradeService  */
        $service = $this->getServiceLocator()->get(VM10619RoleManagementUpgradeService::class);
        $resultJson = $service->create($data);
        return $resultJson;
    }
}
