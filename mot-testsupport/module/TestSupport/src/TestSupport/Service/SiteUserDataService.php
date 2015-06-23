<?php

namespace TestSupport\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommon\UrlBuilder\NotificationUrlBuilder;
use DvsaCommon\UrlBuilder\SiteUrlBuilder;
use TestSupport\FieldValidation;
use TestSupport\Helper\DataGeneratorHelper;
use TestSupport\Helper\NotificationsHelper;
use TestSupport\Helper\RestClientGetterTrait;
use TestSupport\Helper\SitePermissionsHelper;
use TestSupport\Helper\TestDataResponseHelper;
use TestSupport\Model\Account;
use TestSupport\Model\AccountPerson;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\JsonModel;

/**
 * Creates Site Managers OR Site users for use by tests.
 *
 * Should not be deployed in production.
 */
class SiteUserDataService implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;
    use RestClientGetterTrait;

    const STATUS_ID_ACCEPTED = 2;
    const SITE_POSITION_NOTIFICATION_ID = 5;

    /**
     * @var NotificationsHelper
     */
    private $notificationsHelper;

    /**
     * @var SitePermissionsHelper
     */
    private $sitePermissionsHelper;

    public function __construct(
        NotificationsHelper $notificationsHelper,
        SitePermissionsHelper $sitePermissionsHelper
    ) {
        $this->notificationsHelper = $notificationsHelper;
        $this->sitePermissionsHelper = $sitePermissionsHelper;
    }

    /**
     * @param array  $data optional data with differentiator,
     *                     requestor => username and password of AEDM/AED with whom to assign site manager role
     *                     siteIds -> list of VTSs for site manager
     * @param string $role
     *
     * @return JsonModel username of new Site User (Manager Or Admin)
     */
    public function create($data, $role)
    {
        FieldValidation::checkForRequiredFieldsInData(['siteIds'], $data);

        if (!isset($data['personId'])) {
            /** @var $accountService AccountService */
            $accountService = $this->getServiceLocator()->get(AccountService::class);
            $dataGeneratorHelper = DataGeneratorHelper::buildForDifferentiator($data);
            $account = $accountService->createAccount(
                $role,
                $dataGeneratorHelper,
                new AccountPerson($data, $dataGeneratorHelper)
            );
        } else {
            $account = new Account($data);
        }

        $this->nominateAndAcceptRoleAtSites($account, $role, $data);

        return TestDataResponseHelper::jsonOk(
            [
                "message"  => $role . ' created',
                "username" => $account->getUsername(),
                "password" => $account->getPassword(),
                "personId" => $account->getPersonId()
            ]
        );
    }

    /**
     * @param Account $account
     * @param string  $role
     * @param array   $data
     */
    private function nominateAndAcceptRoleAtSites(Account $account, $role, array $data)
    {
        $this->sitePermissionsHelper->addPermissionToSites($account, $role, $data['siteIds']);

        $notifications = $this->notificationsHelper->getNotifications($account);

        $this->notificationsHelper->acceptUnreadNotification(
            $account,
            $notifications,
            self::SITE_POSITION_NOTIFICATION_ID
        );
    }
}
