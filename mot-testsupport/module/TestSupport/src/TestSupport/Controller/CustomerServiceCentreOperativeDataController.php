<?php

namespace TestSupport\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use DvsaCommon\Constants\Role;
use TestSupport\DataGenSupport;
use TestSupport\Helper\TestSupportAccessTokenManager;
use TestSupport\Service\AccountDataService;
use TestSupport\TestDataResponseHelper;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use TestSupport\Service\CSCOService;

/**
 * Creates User account with CUSTOMER_SERVICE_CENTRE_OPERATIVE role for use by tests.
 */
class CustomerServiceCentreOperativeDataController extends BaseTestSupportRestfulController
{

    /**
     * @param null|array $data including
     *                    "diff" string to differentiate scheme management users
     *
     * @return JsonModel username of new tester
     */
    public function create($data)
    {
        $cscoService = $this->getServiceLocator()->get(CSCOService::class);
        $resultJson = $cscoService->create($data);

        return $resultJson;
    }
}
