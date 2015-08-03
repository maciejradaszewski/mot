<?php

namespace TestSupport\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use DvsaCommon\Constants\Role;
use TestSupport\DataGenSupport;
use TestSupport\Helper\TestSupportAccessTokenManager;
use TestSupport\Service\AccountDataService;
use TestSupport\Service\DVLAOperativeService;
use TestSupport\TestDataResponseHelper;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Creates User account with DVLA-OPERATIVE role for use by tests.
 */
class DvlaOperativeDataController extends BaseTestSupportRestfulController
{

    /**
     * @param null|array $data including
     *                    "diff" string to differentiate scheme management users
     *
     * @return JsonModel username of new tester
     */
    public function create($data)
    {
        $cscoService = $this->getServiceLocator()->get(DVLAOperativeService::class);
        $resultJson = $cscoService->create($data);

        return $resultJson;
    }
}
