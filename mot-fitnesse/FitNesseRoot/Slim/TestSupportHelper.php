<?php

use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SchemeManagementUserCredentialsProvider;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\TestSupportUrlBuilder;
use MotFitnesse\Util\UrlBuilder;
use TestSupport\Controller\SpecialNoticeDataController;

class TestSupportHelper
{
    const ENTITY_SCHEME_MANAGER                    = 'schm';
    const ENTITY_SCHEME_USER                       = 'schemeuser';
    const ENTITY_AE                                = 'ae';
    const ENTITY_AEDM                              = 'aedm';
    const ENTITY_AED                               = 'aed';
    const ENTITY_VTS                               = 'vts';
    const ENTITY_TESTER                            = 'tester';
    const ENTITY_INACTIVE_TESTER                   = 'inactivetester';
    const ENTITY_USER                              = 'user';
    const ENTITY_SITE_MANAGER                      = 'sm';
    const ENTITY_SITE_ADMIN                        = 'sa';
    const ENTITY_AREA_OFFICE1                      = 'ao1';
    const ENTITY_AREA_OFFICE2                      = 'ao2';
    const ENTITY_ASSESSOR                          = 'assessor';
    const ENTITY_VEHICLE_EXAMINER                  = 'vehicleexaminer';
    const ENTITY_MOT_TEST                          = 'mottest';
    const ENTITY_CUSTOMER_SERVICE_CENTRE_OPERATIVE = 'csco';
    const ENTITY_DVLA_OPERATIVE                    = 'dvlaop';
    const ENTITY_SLOT_TRANSACTION                  = 'slotTxn';
    const ENTITY_FINANCE_USER                      = 'financeuser';

    const CREDENTIAL_DEFAULT_PASSWORD = TestShared::PASSWORD;

    private $entity;
    private $client;
    private $schemeManagementCredentials;
    private $data;
    private $response;

    public function createSchemeManager($diff = null)
    {
        $data = [];
        $this->pushDiffToData($data, $diff);

        return $this->fetch(self::ENTITY_SCHEME_MANAGER, $data);
    }

    public function createSchemeUser($diff = null)
    {
        $data = [];
        $this->pushDiffToData($data, $diff);

        return $this->fetch(self::ENTITY_SCHEME_USER, $data);
    }

    public function createSlotTransaction($requestorUsername, $params, $diff = null)
    {
        $data = [
            'requestor' => [
                'username' => $requestorUsername,
                'password' => TestShared::PASSWORD
            ]
        ];
        $this->pushDiffToData($data, $diff);
        $data['state'] = array_merge($data, $params);

        return $this->fetch(self::ENTITY_SLOT_TRANSACTION, $data);
    }

    public function createAuthorisedExaminer($requestorUsername, $diff = null, $slots = 100, $isManyVtsTester = false)
    {
        $data = [
            'slots'     => $slots,
            'requestor' => [
                'username'        => $requestorUsername,
                'password'        => TestShared::PASSWORD,
                'isManyVtsTester' => $isManyVtsTester
            ]
        ];
        $this->pushDiffToData($data, $diff);

        return $this->fetch(self::ENTITY_AE, $data);
    }

    public function createAuthorisedExaminerDesignatedManagement(
        $requestorUsername,
        $diff = null,
        $authorisedExaminerIds
    ) {
        $data = [
            'aeIds'     => $authorisedExaminerIds,
            'requestor' => [
                'username' => $requestorUsername,
                'password' => TestShared::PASSWORD
            ]
        ];
        $this->pushDiffToData($data, $diff);

        return $this->fetch(self::ENTITY_AEDM, $data);
    }

    public function createAuthorisedExaminerDelegate(
        $requestorUsername,
        $diff = null,
        $authorisedExaminerIds
    ) {
        $data = [
            'aeIds'     => $authorisedExaminerIds,
            'requestor' => [
                'username' => $requestorUsername,
                'password' => TestShared::PASSWORD
            ]
        ];
        $this->pushDiffToData($data, $diff);

        return $this->fetch(self::ENTITY_AED, $data);
    }

    public function createVehicleExaminer($requestorUsername = null, $diff = null)
    {
        $data = [];

        if (!is_null($requestorUsername)) {
            $data['requestor'] = [
                'username' => $requestorUsername,
                'password' => TestShared::PASSWORD
            ];
        }

        $this->pushDiffToData($data, $diff);

        return $this->fetch(self::ENTITY_VEHICLE_EXAMINER, $data);
    }

    public function createTester(
        $requestorUsername,
        $vtsIds,
        $diff = null,
        $accountClaimRequired = false,
        $testGroup = null,
        $contactEmail = null
    ) {
        $data = [
            'siteIds'              => $vtsIds,
            'requestor'            => [
                'username' => $requestorUsername,
                'password' => TestShared::PASSWORD,
            ],
            'accountClaimRequired' => $accountClaimRequired,
        ];
        $this->pushDiffToData($data, $diff);

        if (!is_null($testGroup)) {
            $data['testGroup'] = $testGroup;
        }

        if ($contactEmail) {
            $data['contactEmail'] = $contactEmail;
        }

        return $this->fetch(self::ENTITY_TESTER, $data);
    }


    public function createInactiveTester(
        $requestorUsername,
        $vtsIds
    ) {
        $data = [
            'siteIds'              => $vtsIds,
            'requestor'            => [
                'username' => $requestorUsername,
                'password' => TestShared::PASSWORD,
            ],
        ];

        return $this->fetch(self::ENTITY_INACTIVE_TESTER, $data);
    }

    public function suspendTester(
        $personId
    ) {
        $data = [
            'personId'             => $personId,
        ];

        return $this->fetch(self::ENTITY_INACTIVE_TESTER, $data);
    }

    public function createSiteManager($requestorUsername, $vtsIds, $diff = null)
    {
        $data = [
            'siteIds'   => $vtsIds,
            'requestor' => [
                'username' => $requestorUsername,
                'password' => TestShared::PASSWORD
            ]
        ];
        $this->pushDiffToData($data, $diff);

        return $this->fetch(self::ENTITY_SITE_MANAGER, $data);
    }

    public function createSiteAdmin($requestorUsername, $vtsIds, $diff = null)
    {
        $data = [
            'siteIds'   => $vtsIds,
            'requestor' => [
                'username' => $requestorUsername,
                'password' => TestShared::PASSWORD
            ]
        ];
        $this->pushDiffToData($data, $diff);

        return $this->fetch(self::ENTITY_SITE_ADMIN, $data);
    }

    public function createFinanceUser()
    {
        return $this->fetch(self::ENTITY_FINANCE_USER, []);
    }

    public function createVehicleTestingStation($requestorUsername, $authorisedExaminerId, $diff = null, $params = [])
    {
        $data = [
            'aeId'      => $authorisedExaminerId,
            'requestor' => [
                'username' => $requestorUsername,
                'password' => TestShared::PASSWORD
            ]
        ];
        $data = array_merge($data, $params);

        $this->pushDiffToData($data, $diff);

        return $this->fetch(self::ENTITY_VTS, $data);
    }

    public function createActiveMotTest($requestorUsername, $vtsId, $vehicleId, $diff)
    {
        return $this->createMotTest(
            $requestorUsername, $vtsId, $vehicleId, MotTestStatusName::ACTIVE, $diff
        );
    }

    /**
     * @param string $vehicleClass
     * @param string $status
     *
     * @return array
     */
    public function createIndependentMotTest(
        $vehicleClass,
        $testType = MotTestTypeCode::NORMAL_TEST,
        $status = MotTestStatusName::PASSED,
        $rfrs = []
    ) {
        $schememgt = $this->createSchemeManager()['username'];
        $ae        = $this->createAuthorisedExaminer(
            $this->createAreaOffice1User()['username'], null, 1000
        );

        $site = $this->createVehicleTestingStation(
            $this->createAreaOffice1User()['username'],
            $ae['id'],
            'vts'
        );

        $tester    = $this->createTester($schememgt, [$site['id']]);
        $vehicleId = (new VehicleTestHelper(FitMotApiClient::create($tester['username'], TestShared::PASSWORD)))
            ->generateVehicle(['testClass' => $vehicleClass]);

        return $this->createMotTest(
            $tester['username'], $site['id'], $vehicleId, $status, null, 1234, $this->createMotTestDateSetParam(),
            $testType, $rfrs
        );

    }

    private function createMotTestDateSetParam($dateOfTestStr = null)
    {
        if ($dateOfTestStr != null) {
            $dateOfTest = DateUtils::toDate($dateOfTestStr);
        } else {
            $dateOfTest = DateUtils::today();
        }
        $startDateTime = DateTimeApiFormat::dateTime($dateOfTest);
        $issueDate     = DateTimeApiFormat::date($dateOfTest);
        $expiryDate    = DateTimeApiFormat::date(
            $dateOfTest
                ->add(new \DateInterval('P1Y'))
                ->sub(new \DateInterval('P1D'))
        );

        return [
            'startDate'     => $startDateTime,
            'issueDate'     => $issueDate,
            'completedDate' => $startDateTime,
            'expiryDate'    => $expiryDate
        ];
    }

    public function abortMotTest($requestorUsername, $motTestNumber, $reasonId)
    {
        return $this->changeMotTestStatus(
            $requestorUsername,
            $motTestNumber,
            MotTestStatusName::ABORTED,
            $reasonId
        );
    }

    public function confirmMotTest($requestorUsername, $motTestNumber, $passed = true)
    {
        return $this->changeMotTestStatus(
            $requestorUsername,
            $motTestNumber,
            $passed ? MotTestStatusName::PASSED : MotTestStatusName::FAILED
        );
    }

    private function changeMotTestStatus($requestorUsername, $motTestNumber, $status, $reasonId = null)
    {
        $credentialsProvider = new CredentialsProvider($requestorUsername, self::CREDENTIAL_DEFAULT_PASSWORD);
        $client              = $this->getClient($credentialsProvider);

        $data = ['status' => $status];
        if (!is_null($reasonId)) {
            $data['reasonForCancelId'] = $reasonId;
        }

        if (MotTestStatusName::PASSED === $status
            || MotTestStatusName::FAILED === $status
        ) {
            $data['oneTimePassword'] = 123456;
        }

        return $client->post(
            (new UrlBuilder())->motTest()->routeParam("motTestNumber", $motTestNumber)->motTestStatus(),
            $data
        );
    }

    public function createMotTest(
        $requestorUsername,
        $vtsId,
        $vehicleId,
        $outcome,
        $diff = null,
        $mileage = 2000,
        // this needs to be sorted out
        $dateSet
        = [
            'startDate'     => '2012-12-11T23:12:31Z',
            'issueDate'     => '2012-12-12',
            'completedDate' => '2012-12-13T23:12:33Z',
            'expiryDate'    => '2013-12-14'
        ],
        $testType = null,
        $rfrs = [],
        $retest = null
    ) {
        $data = [
            'vtsId'     => $vtsId,
            'vehicleId' => $vehicleId,
            'motTest'   => [
                'testType'      => $testType,
                'mileage'       => $mileage,
                'outcome'       => $outcome,
                'issueDate'     => $dateSet['issueDate'],
                'startDate'     => $dateSet['startDate'],
                'completedDate' => $dateSet['completedDate'],
                'expiryDate'    => $dateSet['expiryDate'],
                'rfrs'          => $rfrs,
            ],
            'retest'    => $retest,
            'requestor' => [
                'username' => $requestorUsername,
                'password' => TestShared::PASSWORD
            ],
        ];
        $this->pushDiffToData($data, $diff);

        return $this->fetch(self::ENTITY_MOT_TEST, $data);
    }

    public function createUser($userData = [])
    {
        return $this->fetch(self::ENTITY_USER, $userData);
    }

    public function createAreaOffice1User()
    {
        return $this->fetch(self::ENTITY_AREA_OFFICE1, []);
    }

    public function createAreaOffice2User()
    {
        return $this->fetch(self::ENTITY_AREA_OFFICE2, []);
    }

    public function createCustomerServiceCentreOperative()
    {
        return $this->fetch(self::ENTITY_CUSTOMER_SERVICE_CENTRE_OPERATIVE, []);
    }

    public function createDvlaOperative()
    {
        return $this->fetch(self::ENTITY_DVLA_OPERATIVE, []);
    }

    private function fetch($entity, $data)
    {
        $this->setCreate($entity);
        $this->setData(json_encode($data));

        return $this->execute()->response();
    }

    private function pushDiffToData(&$data, $diff = null)
    {
        if (!is_null($diff)) {
            $data['diff'] = $diff;
        }
    }

    /**
     * @return SchemeManagementUserCredentialsProvider
     */
    private function getSchemeManagementCredentials()
    {
        if (is_null($this->schemeManagementCredentials)) {
            $this->schemeManagementCredentials = new MotFitnesse\Util\SchemeManagementUserCredentialsProvider();
        }

        return $this->schemeManagementCredentials;
    }

    /**
     * @param CredentialsProvider $credentialsProvider (null by default)
     *
     * @return FitMotApiClient
     */
    private function getClient($credentialsProvider = null)
    {
        if (is_null($credentialsProvider)) {
            $this->client = FitMotApiClient::createForCreds($this->getSchemeManagementCredentials());
        } else {
            $this->client = FitMotApiClient::createForCreds($credentialsProvider);
        }

        return $this->client;
    }

    private function getUrlBuilder()
    {
        $urlBuilder = new TestSupportUrlBuilder();

        $shortCutMethodName = $this->entity;

        if (!method_exists($urlBuilder, $shortCutMethodName)) {
            throw new LogicException(
                '"' . $this->entity . '" method is not exists in the "TestSupportUrlBuilder" class as a shortcut'
            );
        }

        $urlBuilder->$shortCutMethodName();

        return $urlBuilder;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    private function getData()
    {
        return json_decode($this->data, true);
    }

    public function setCreate($entity)
    {
        $this->entity = $entity;
    }

    private function response()
    {
        return $this->response;
    }

    private function execute()
    {
        $result         = $this->getClient()->post($this->getUrlBuilder(), $this->getData());
        $this->response = $result;

        return $this;
    }

    private function getSpecialNoticeIssueNumber()
    {
        return $this->specialNoticeIssueNumber++;
    }

    public function createSpecialNotice($publishDateRaw, $isPublished, $title)
    {
        $urlBuilder = new TestSupportUrlBuilder();
        $urlBuilder->testSupport()->specialNotice()->createSpecialNotice();

        $publishDate = new DateTime($publishDateRaw);

        $response = $this->getClient()->post(
            $urlBuilder,
            [
                'title'                 => $title,
                'issueYear'             => $publishDate->format("Y"),
                'issueDate'             => $publishDate->format("Y-m-d"),
                'expiryDate'            => $publishDate->format("Y-m-d"),
                'internalPublishDate'   => $publishDate->format("Y-m-d"),
                'externalPublishDate'   => $publishDate->format("Y-m-d"),
                'acknowledgementPeriod' => '5',
                'noticeText'            => 'notice text',
                'isPublished'           => $isPublished ? 1: 0,
                'isDeleted'             => 0,
                'createdBy'             => 1,
            ]
        );

        if ($isPublished) {
            $response = $this->getClient(new CredentialsProvider(TestShared::USERNAME_SCHEMEUSER))->post(
                UrlBuilder::of()->specialNoticeContentPublish()->routeParam(
                    'id', $response['id']
                ), []
            );
        }

        return $response;
    }

    public function broadcastSpecialNotice($username, $specialNoticeContentId, $isAcknowledged)
    {
        $urlBuilder = new TestSupportUrlBuilder();
        $urlBuilder->testSupport()->specialNotice()->broadcastSpecialNotice();

        $response = $this->getClient()->post(
            $urlBuilder,
            [
                'username'               => $username,
                'specialNoticeContentId' => $specialNoticeContentId,
                'isAcknowledged'         => $isAcknowledged
            ]
        );

        return $response;
    }

    public function createExpiredNoticeForUser($username, $title)
    {

        $time        = new DateTime();
        $publishDate = $time->modify('-1 year')->format('Y-m-d');

        $notice = $this->createSpecialNotice($publishDate, true, $title);

        $this->broadcastSpecialNotice(
            $username,
            $notice['id'],
            false
        );
    }

    public function createEvent($id, $type)
    {
        $urlBuilder = new TestSupportUrlBuilder();
        $urlBuilder->testSupport()->createEvent();
        $response = $this->getClient()->post(
            $urlBuilder,
            [
                'type'      => $type,
                'entity-id' => $id,
            ]
        );

        return $response;
    }

    public function generateSecurityQuestion($person, $securityQuestion, $answer)
    {
        $urlBuilder = new TestSupportUrlBuilder();
        $urlBuilder->testSupport()->generateSecurityQuestion();
        $response = $this->getClient()->post(
            $urlBuilder,
            [
                'person'   => $person,
                'question' => $securityQuestion,
                'answer'   => $answer
            ]
        );

        return $response;
    }

    public function resetPassword($requestorUsername, $userId)
    {
        $urlBuilder = new TestSupportUrlBuilder();
        $urlBuilder->testSupport()->resetPassword();
        $response = $this->getClient()->post(
            $urlBuilder,
            [
                'userId'    => $userId,
                'requestor' => [
                    'username' => $requestorUsername,
                    'password' => TestShared::PASSWORD
                ]
            ]
        );
        return $response;
    }
}
