<?php

namespace TestSupport\Service;

use TestSupport\Helper\NotificationsHelper;
use TestSupport\Helper\TestSupportAccessTokenManager;
use DvsaCommon\Enum\SiteBusinessRoleCode;
use Zend\View\Model\JsonModel;
use TestSupport\Service\AccountService;
use TestSupport\FieldValidation;
use TestSupport\Helper\TestSupportRestClientHelper;
use TestSupport\Model\Account;
use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode;
use DvsaCommon\Utility\ArrayUtils;
use TestSupport\Helper\TestDataResponseHelper;
use Doctrine\ORM\EntityManager;
use TestSupport\Helper\DataGeneratorHelper;
use TestSupport\Helper\SitePermissionsHelper;
use TestSupport\Model\AccountPerson;

class TesterService
{
    const QLFD_STATUS_ID = 9;
    const SITE_POSITION_NOTIFICATION_ID = 5;

    /**
     * @var TestSupportRestClientHelper
     */
    protected $testSupportRestClientHelper;

    /**
     * @var AccountService
     */
    protected $accountService;

    /**
     * @var NotificationsHelper
     */
    protected $notificationsHelper;

    /**
     * @var SitePermissionsHelper
     */
    protected $sitePermissionsHelper;

    /**
     * @var AEService
     */
    protected $aeService;

    /**
     * @var VtsService
     */
    protected $vtsService;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var array
     */
    protected $testerStatuses = [
        AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED,
        AuthorisationForTestingMotStatusCode::REFRESHER_NEEDED,
        AuthorisationForTestingMotStatusCode::SUSPENDED,
        AuthorisationForTestingMotStatusCode::QUALIFIED
    ];

    /**
     * @param TestSupportRestClientHelper $testSupportRestClientHelper
     * @param NotificationsHelper $notificationsHelper
     * @param SitePermissionsHelper $sitePermissionsHelper
     * @param AccountService $accountService
     * @param EntityManager $entityManager
     */
    public function __construct(
        TestSupportRestClientHelper $testSupportRestClientHelper,
        NotificationsHelper $notificationsHelper,
        SitePermissionsHelper $sitePermissionsHelper,
        AccountService $accountService,
        EntityManager $entityManager
    ) {
        $this->testSupportRestClientHelper = $testSupportRestClientHelper;
        $this->accountService = $accountService;
        $this->entityManager = $entityManager;
        $this->notificationsHelper = $notificationsHelper;
        $this->sitePermissionsHelper = $sitePermissionsHelper;
    }

    /**
     * Create a tester with the data supplied
     *
     * @param array $data
     * @return JsonModel
     */
    public function create(array $data)
    {
        FieldValidation::checkForRequiredFieldsInData(['siteIds'], $data);
        TestSupportAccessTokenManager::addSchemeManagerAsRequestorIfNecessary($data);

        if (!isset($data['personId'])) {
            $dataGeneratorHelper = DataGeneratorHelper::buildForDifferentiator($data);
            if (isset($data['contactEmail'])) {
                $data['emailAddress'] = $data['contactEmail'];
            }

            $account = $this->accountService->createAccount(
                SiteBusinessRoleCode::TESTER,
                $dataGeneratorHelper,
                new AccountPerson($data, $dataGeneratorHelper)
            );
        } else {
            $account = new Account($data);
        }

        $qualified = AuthorisationForTestingMotStatusCode::QUALIFIED;
        if ($this->hasTesterStatus(ArrayUtils::tryGet($data, 'status', $qualified))) {
            $this->sendNominationsForTesterAndAcceptThem($account, $data);
        }

        return TestDataResponseHelper::jsonOk([
            "message"  => "Tester created",
            "username" => $account->getUsername(),
            "password" => $account->getPassword(),
            "personId" => $account->getPersonId(),
            "firstName"=> $account->getFirstName(),
            "surname"  => $account->getSurname()
        ]);
    }

    /**
     * Only tester/tester-applicants who have proceeded a certain way through the approval
     * process get the rest of the setup
     * @param string $status
     * @return bool
     */
    private function hasTesterStatus($status)
    {
        return in_array($status, $this->testerStatuses);
    }

    private function sendNominationsForTesterAndAcceptThem(Account $account, $data)
    {
        $testGroups = [1, 2];
        if (isset($data['testGroup'])) {
            $testGroups = [$data['testGroup']];
        }

        $this->finishCreatingTesterWithHacking($account->getPersonId(), $testGroups);

        $this->sitePermissionsHelper->addPermissionToSites($account, SiteBusinessRoleCode::TESTER, $data['siteIds']);

        $notifications = $this->notificationsHelper->getNotifications($account);
        $this->notificationsHelper->acceptUnreadNotification(
            $account,
            $notifications,
            self::SITE_POSITION_NOTIFICATION_ID
        );
    }

    /**
     * @param $personId
     * @param $testGroups
     */
    private function finishCreatingTesterWithHacking($personId, $testGroups)
    {
        // @todo this is pure dirt, find a controller action to do this maybe via enforcement
        $stmt = $this->entityManager->getConnection()->prepare(
            "INSERT INTO auth_for_testing_mot (status_id, person_id, vehicle_class_id, created_by)
             VALUES (?, ?, ?, 1)"
        );

        $stmt->bindValue(1, self::QLFD_STATUS_ID);
        $stmt->bindValue(2, $personId);

        foreach ($testGroups as $testGroup) {
            if ($testGroup == 1) {
                foreach ([1, 2] as $cls) {
                    $stmt->bindValue(3, $cls);
                    $stmt->execute();
                }
            } else {
                foreach ([3, 4, 5, 7] as $cls) {
                    $stmt->bindValue(3, $cls);
                    $stmt->execute();
                }
            }
        }
    }
}
