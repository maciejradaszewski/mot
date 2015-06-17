<?php

namespace TestSupport\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use DvsaCommon\Constants\Role;
use TestSupport\Helper\TestSupportAccessTokenManager;
use TestSupport\Service\AccountDataService;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use TestSupport\Service\AreaOffice2Service;

/**
 * Creates User account with AREA-OFFICE-2 role for use by tests.
 * Should not be deployed in production.
 */
class AreaOffice2DataController extends BaseTestSupportRestfulController
{
    /**
     * @param mixed $data including "diff" string to differentiate scheme management users
     * @return JsonModel username of new AO2 user
     */
    public function create($data)
    {
        $areaOffice2Service = $this->getServiceLocator()->get(AreaOffice2Service::class);
        $resultJson = $areaOffice2Service->create($data);
        return $resultJson;
    }
}
