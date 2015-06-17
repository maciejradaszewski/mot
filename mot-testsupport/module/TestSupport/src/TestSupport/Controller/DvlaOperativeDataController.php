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
        TestSupportAccessTokenManager::addSchemeManagerAsRequestorIfNecessary($data);

        /** @var $accountHelper AccountDataService */
        $accountHelper = $this->getServiceLocator()->get(AccountDataService::class);

        $resultJson =$accountHelper->create($data, Role::DVLA_OPERATIVE);
        $accountHelper->addRole($resultJson->data['personId'], Role::DVLA_OPERATIVE);
        return $resultJson;
    }
}
