<?php

namespace TestSupport\Controller;

use DvsaCommon\Constants\Role;
use TestSupport\Helper\TestSupportAccessTokenManager;
use TestSupport\Service\VehicleExaminerService;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Creates User account with VEHICLE-EXAMINER role for use by tests.
 *
 * Should not be deployed in production.
 */
class VehicleExaminerDataController extends BaseTestSupportRestfulController
{
    /**
     * @param mixed $data including
     *                    "diff" string to differentiate scheme management users
     *
     * @return JsonModel username of new tester
     */
    public function create($data)
    {
        $vehicleExaminerService = $this->getServiceLocator()->get(VehicleExaminerService::class);
        $resultJson = $vehicleExaminerService->create($data);
        return $resultJson;
    }
}
