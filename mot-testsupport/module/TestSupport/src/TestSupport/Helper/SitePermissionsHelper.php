<?php

namespace TestSupport\Helper;

use TestSupport\Model\Account;
use TestSupport\Helper\TestSupportRestClientHelper;
use DvsaCommon\UrlBuilder\SiteUrlBuilder;

class SitePermissionsHelper
{
    /**
     * @var Account
     */
    private $account;

    /**
     * @var string
     */
    private $role;

    /**
     * @var  TestSupportRestClientHelper
     */
    private $testSupportRestClientHelper;

    /**
     * @param TestSupportRestClientHelper $testSupportRestClientHelper
     * @see DvsaCommon\Enum\SiteBusinessRoleCode
     */
    public function __construct(TestSupportRestClientHelper $testSupportRestClientHelper)
    {
        $this->testSupportRestClientHelper = $testSupportRestClientHelper;
    }

    /**
     * Adds the relevant role to the list of siteId's provided
     * @param Account $account
     * @param string $role
     * @param array $siteIds
     * @return void
     * @throws \Exception if the rest client responds in an unexpected fashion
     */
    public function addPermissionToSites(Account $account, $role, array $siteIds)
    {
        $data = [];
        TestSupportAccessTokenManager::addSchemeManagerAsRequestorIfNecessary($data);
        $restClient = $this->testSupportRestClientHelper->getJsonClient($data);

        foreach ($siteIds as $id) {
            $positionPath = SiteUrlBuilder::site($id)->position()->toString();

            $return = $restClient->post($positionPath, [
                'nomineeId' => $account->getPersonId(),
                'roleCode'  => $role,
            ]);
            if (! isset($return['data'])) {
                throw new \Exception('Failed to add permission to siteId '.$id);
            }
        }
    }
}