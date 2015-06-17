<?php

namespace TestSupport\Controller;

use Doctrine\ORM\EntityManager;
use DvsaCommonApi\Service\EntityFinderTrait;
use TestSupport\Helper\TestSupportAccessTokenManager;
use TestSupport\Service\AccountDataService;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Creates a simple user for use by tests.
 *
 * Should not be deployed in production.
 */
class UserDataController extends BaseTestSupportRestfulController
{
    /**
     * @param mixed $data including
     *                    "diff" string to differentiate scheme management users
     *
     * @return void|JsonModel username of new tester
     */
    public function create($data)
    {
        TestSupportAccessTokenManager::addSchemeManagerAsRequestorIfNecessary($data);

        /** @var $accountHelper AccountDataService */
        $accountHelper = $this->getServiceLocator()->get(AccountDataService::class);

        return $accountHelper->create($data);
    }
}
