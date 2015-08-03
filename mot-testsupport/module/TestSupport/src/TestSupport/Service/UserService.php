<?php

namespace TestSupport\Service;

use TestSupport\Helper\NotificationsHelper;
use TestSupport\Service\AccountDataService;
use TestSupport\Helper\TestSupportAccessTokenManager;
use DvsaCommon\Constants\Role;

class UserService
{

    /**
     * @var AccountDataService
     */
    protected $accountDataService;

    /**
     * @var NotificationsHelper
     */
    private $notificationsHelper;

    public function __construct(
        AccountDataService $accountDataService,
        NotificationsHelper $notificationsHelper
    ) {
        $this->accountDataService = $accountDataService;
        $this->notificationsHelper = $notificationsHelper;
    }

    /**
     * Create a basic user with the data supplied
     *
     * @param array $data
     * @return JsonModel
     */
    public function create(array $data)
    {
        TestSupportAccessTokenManager::addSchemeManagerAsRequestorIfNecessary($data);
        $resultJson = $this->accountDataService->create($data, Role::USER);
        $this->accountDataService->addRole($resultJson->data['personId'], Role::USER);
        return $resultJson;
    }
}