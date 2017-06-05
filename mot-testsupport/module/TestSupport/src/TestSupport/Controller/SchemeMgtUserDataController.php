<?php

namespace TestSupport\Controller;

use DvsaCommon\Constants\Role;
use TestSupport\Helper\TestSupportAccessTokenManager;
use TestSupport\Service\AccountDataService;
use Zend\View\Model\JsonModel;

/**
 * Creates a DVSA scheme management user for use by tests.
 *
 * Should not be deployed in production.
 */
class SchemeMgtUserDataController extends BaseTestSupportRestfulController
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
        $resultJson = $accountHelper->create($data, Role::DVSA_SCHEME_MANAGEMENT);
        $accountHelper->addRole($resultJson->data['personId'], Role::DVSA_SCHEME_MANAGEMENT);

        return $resultJson;
    }
}
