<?php

namespace TestSupport\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use DvsaCommon\Obfuscate\ParamObfuscator;
use TestSupport\Helper\TestDataResponseHelper;
use TestSupport\Service\DvlaVehicleService;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * DvlaVehicle related methods
 *
 * Should not be deployed in production.
 */
class DvlaVehicleDataController extends BaseTestSupportRestfulController
{

    public function createAction()
    {
        $data = get_object_vars(json_decode($this->getRequest()->getContent()));

        /** @var DvlaVehicleService $vehicleService */
        $vehicleService = $this->getServiceLocator()->get(DvlaVehicleService::class);

        try {
            $vehicleId = $vehicleService->save($data);
        } catch (\Exception $e) {
            return TestDataResponseHelper::jsonError($e->getMessage());
        }

        return TestDataResponseHelper::jsonOk($vehicleId);
    }

}
