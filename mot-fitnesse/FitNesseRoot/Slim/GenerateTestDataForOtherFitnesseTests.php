<?php

use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Enum\ReasonForCancelId;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\VehicleClassGroupCode;
use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode as AuthorisationStatus;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Testing\Vehicle\VehicleHelper;

/**
 * Helper fitnesse test to be used before other fitnesse tests to create any data required.
 * Creates schememgt, ae, vts, tester, vehicle, mot test.
 *
 * All columns in the table are optional, the data for the unused columns is still created but only the relevant data
 * for each fitnesse test needs to be displayed.
 *
 *
 * Sample table with all available columns:
 *
 * !| GenerateTestDataForOtherFitnesseTests                                                                                                                                                                                                                                                                               |
 * |schememgtUsername?|schememgtId?|slots |orgId|orgId?|aedmUsername?|siteId|siteId?|siteMngrUsername?|siteMngrId?|testerAuthorisationStatusForGroupA|testerAuthorisationStatusForGroupB|testerUsername|testerUsername?|testerUserId?|testerPassword?|testerName?|inactiveTesterUsername?|vin|vrm|manufactureDate|firstRegistrationDate|vehicleTestClass|vehicleId|vehicleId?|status  |DateOfTest|isRetest|retestStatus|motTestNumber?|
 * |                  |            |(1024)|     |      |             |      |       |                 |           |${Qualified}                      |${DemoTestedNeeded}               |               |             |(Password1)    |           |                       |   |   |               |                     |(4)             |         |          |(PASSED)|(today)   |(false) |(PASSED)    |              |
 */
class GenerateTestDataForOtherFitnesseTests
{
    const DEFAULT_GROUP_A_STATUS = AuthorisationStatus::QUALIFIED;
    const DEFAULT_GROUP_B_STATUS = AuthorisationStatus::QUALIFIED;
    /** @var TestSupportHelper */
    private $testSupportHelper;
    private $aeRef;
    private $slots;
    private $orgId;
    private $orgData = [];
    private $siteId;
    private $siteNumber;
    private $siteName;
    private $siteAddress;
    private $sitePostcode;
    private $siteTown;
    private $siteCountry;
    private $testerAuthorisationStatusGroupA = self::DEFAULT_GROUP_A_STATUS;
    private $testerAuthorisationStatusGroupB = self::DEFAULT_GROUP_B_STATUS;

    private $vehicleId;
    private $vehicleData = [];
    private $dvlaVehicleId;
    private $dvlaVehicleData = [];

    private $status;
    private $dateOfTest;
    private $isRetest;
    private $retestStatus;

    private $userSchemeMgt;
    private $userSchemeUser;
    private $userAreaOffice1;
    private $userAreaOffice2;
    private $userAed;
    private $userAedm;
    private $userVehicleExaminer;
    private $userDvlaOper;
    private $userCsco;
    private $userTester;
    private $userSiteMngr;
    private $userSiteAdmin;
    private $userFinanceUser;

    private $numberOfEvents = 1;

    private $securityQuestionOneId;
    private $securityQuestionOne;
    private $securityQuestionTwoId;
    private $securityQuestionTwo;

    private $testerContactEmail;

    public function __construct()
    {
        $this->testSupportHelper = new TestSupportHelper();
    }

    public function reset()
    {
        $this->userSchemeMgt = null;
        $this->userAreaOffice1 = null;
        $this->userAreaOffice2 = null;
        $this->userAed = null;
        $this->userAedm = null;
        $this->userTester = null;
        $this->userSiteMngr = null;
        $this->userSiteAdmin = null;
        $this->userFinanceUser = null;

        $this->slots = 1024;
        $this->orgId = null;
        $this->siteId = null;
        $this->testerAuthorisationStatusGroupA = self::DEFAULT_GROUP_A_STATUS;
        $this->testerAuthorisationStatusGroupB = self::DEFAULT_GROUP_B_STATUS;
        $this->vehicleId = null;
        $this->vehicleData = [];
        $this->status = MotTestStatusName::PASSED;
        $this->dateOfTest = DateTimeApiFormat::date(new \DateTime());
        $this->isRetest = false;
        $this->retestStatus = MotTestStatusName::PASSED;

        $this->testerContactEmail = null;
    }

    /**
     * When a new tester account is created, this will cause a contact account to be
     * generated having the given email address. If it is prefixed with either the
     * value "work:" or "personal:" then it will be created as that type. The default
     * is "personal":
     *
     * @param $value string
     *
     */
    public function setContactEmail($value)
    {
        $this->testerContactEmail = $value;
    }

    public function setSlots($slots)
    {
        if ($slots !== '') {
            $this->slots = $slots;
        }
    }

    public function setOrgId($orgId)
    {
        if ($orgId !== '') {
            $this->orgId = $orgId;
        }
    }

    public function orgId()
    {
        if ($this->orgId === null) {
            $org = $this->testSupportHelper->createAuthorisedExaminer(
                $this->areaOfficeUsername(),
                null,
                $this->slots
            );
            $this->orgId = $org['id'];
            $this->orgData = $org;
        }

        return $this->orgId;
    }

    public function setSiteId($siteId)
    {
        if ($siteId !== '') {
            $this->siteId = $siteId;
        }
    }

    public function setSiteName($siteName)
    {
        if ($siteName !== '') {
            $this->siteName = $siteName;
        }
    }

    public function setSiteAddress($siteAddress)
    {
        $this->siteAddress = $siteAddress;
    }

    public function setSitePostcode($sitePostcode)
    {
        $this->sitePostcode = $sitePostcode;
    }

    public function setSiteTown($siteTown)
    {
        $this->siteTown = $siteTown;
    }

    public function setSiteCountry($siteCountry)
    {
        $this->siteCountry = $siteCountry;
    }

    public function siteId()
    {
        if ($this->siteId === null) {
            $site = $this->testSupportHelper->createVehicleTestingStation(
                $this->areaOfficeUsername(),
                $this->orgId(),
                null,
                [
                    'siteName' => $this->siteName,
                    'addressLine1' => $this->siteAddress,
                    'postcode' => $this->sitePostcode,
                    'town' => $this->siteTown,
                    'country' => $this->siteCountry,
                ]
            );
            $this->siteId = $site['id'];
            $this->siteNumber = $site['siteNumber'];
        }

        return $this->siteId;
    }

    public function siteNumber()
    {
        $this->siteId();

        return $this->siteNumber;
    }

    /**
     * @param string $status
     * @see DvsaCommon\Enum\AuthorisationForTestingMotStatusCode fro the list of valid codes
     */
    public function setTesterAuthorisationStatusForGroupA($status)
    {
        $this->testerAuthorisationStatusGroupA = $status;
    }

    /**
     * @param string $status
     * @see DvsaCommon\Enum\AuthorisationForTestingMotStatusCode fro the list of valid codes
     */
    public function setTesterAuthorisationStatusForGroupB($status)
    {
        $this->testerAuthorisationStatusGroupB = $status;
    }

    public function setColour($colour)
    {
        if ($colour !== '') {
            $this->vehicleData['colour'] = $colour;
        }
    }

    public function setMake($make)
    {
        if ($make !== '') {
            $this->vehicleData['make'] = $make;
        }
    }

    public function setMakeCode($makeCode)
    {
        $this->dvlaVehicleData['make_code'] = $makeCode;
    }

    public function setMakeInFull($makeInFull)
    {
        $this->dvlaVehicleData['make_in_full'] = $makeInFull;
    }

    public function setModel($model)
    {
        if ($model !== '') {
            $this->vehicleData['model'] = $model;
        }
    }

    public function setModelCode($modelCode)
    {
        $this->dvlaVehicleData['model_code'] = $modelCode;
    }

    public function setVrm($vrm)
    {
        if ($vrm !== '') {
            $this->vehicleData['registrationNumber'] = $vrm;
        }
    }

    public function vrm()
    {
        $this->vehicleId();

        return $this->vehicleData['registrationNumber'];
    }

    public function setManufactureDate($manufacturedPre1960)
    {
        if ($manufacturedPre1960 !== '') {
            $this->vehicleData['dateOfManufacture'] = $manufacturedPre1960;
        }
    }

    public function setFirstRegistrationDate($firstRegistrationDate)
    {
        if ($firstRegistrationDate !== '') {
            $this->vehicleData['firstRegistrationDate'] = $firstRegistrationDate;
        }
    }

    public function setVin($vin)
    {
        if ($vin !== '') {
            $this->vehicleData['vin'] = $vin;
        }
    }

    public function vin()
    {
        $this->vehicleId();

        return $this->vehicleData['vin'];
    }

    public function setVehicleTestClass($value)
    {
        if ($value !== '') {
            $this->vehicleData['testClass'] = $value;
        }
    }

    public function setVehicleId($vehicleId)
    {
        if ($vehicleId !== '') {
            $this->vehicleId = $vehicleId;
        }
    }

    public function setDateOfFirstUse($dateOfFirstUse)
    {
        if ($dateOfFirstUse !== '') {
            $this->vehicleData['dateOfFirstUse'] = $dateOfFirstUse;
        }
    }

    public function vehicleId()
    {
        if ($this->vehicleId === null) {
            $vehicleTestHelper = (new VehicleTestHelper(
                FitMotApiClient::create($this->testerUsername(), TestShared::PASSWORD)
            ));

            if (!isset($this->vehicleData['vin'])) {
                $this->vehicleData['vin'] = VehicleHelper::generateVin();
            }

            if (!isset($this->vehicleData['registrationNumber'])) {
                $this->vehicleData['registrationNumber'] = VehicleHelper::generateVrm();
            }

            $this->vehicleId = $vehicleTestHelper->generateVehicle($this->vehicleData);
        }

        return $this->vehicleId;
    }

    public function dvlaVehicleId()
    {
        $vehicleTestHelper = (new VehicleTestHelper(
            FitMotApiClient::create($this->testerUsername(), TestShared::PASSWORD)
        ));

        if (!isset($this->dvlaVehicleData['vin'])) {
            $this->dvlaVehicleData['vin'] = VehicleHelper::generateVin();
        }

        if (!isset($this->dvlaVehicleData['registration'])) {
            $this->dvlaVehicleData['registration'] = VehicleHelper::generateVrm();
        }

        $this->dvlaVehicleId = $vehicleTestHelper->generateDvlaVehicle($this->dvlaVehicleData);

        return $this->dvlaVehicleId;
    }

    public function setStatus($status)
    {
        if ($status !== '') {
            $allowedStatuses = [
                MotTestStatusName::PASSED,
                MotTestStatusName::ACTIVE,
                MotTestStatusName::ABORTED,
                MotTestStatusName::FAILED
            ];

            if (in_array($status, $allowedStatuses)) {
                $this->status = $status;
            } else {
                throw new \InvalidArgumentException(
                    'Invalid status value. Allowed values: ' . join(', ', $allowedStatuses)
                );
            }
        }
    }

    public function setDateOfTest($value)
    {
        if ($value !== '') {
            $this->dateOfTest = $value;
        }
    }

    public function setIsRetest($value)
    {
        $this->isRetest = ($value === 'true');
    }

    public function motTestNumber()
    {
        $retest = null;
        if ($this->isRetest) {
            $retest = $this->createMotReTestParameters();
            $this->status = MotTestStatusName::FAILED;
        }

        $doAbort = false;
        if ($this->status === MotTestStatusName::ABORTED) {
            $doAbort = true;
            $this->status = MotTestStatusName::ACTIVE;
        }

        $motTest = $this->testSupportHelper->createMotTest(
            $this->testerUsername(),
            $this->siteId(),
            $this->vehicleId(),
            $this->status,
            null,
            1234,
            $this->createMotTestDateSetParam(),
            null,
            null,
            $retest
        );

        if ($doAbort) {
            $this->testSupportHelper->abortMotTest(
                $this->testerUsername(),
                $motTest['motTestNumber'],
                ReasonForCancelId::LOCTN
            );
        }

        return $motTest['motTestNumber'];
    }

    private function createTesterAuthorisationStatus()
    {
        $groupedAuthorisationStatus = [
            VehicleClassGroupCode::BIKES => $this->testerAuthorisationStatusGroupA,
            VehicleClassGroupCode::CARS_ETC => $this->testerAuthorisationStatusGroupB,
        ];

        return $groupedAuthorisationStatus;
    }

    public function setRetestStatus($retestStatus)
    {
        if ($retestStatus !== '') {
            $this->retestStatus = $retestStatus;
        }
    }

    private function createMotTestDateSetParam()
    {
        $expiryDate = DateTimeApiFormat::date(
            DateUtils::toDate($this->dateOfTest)
                ->add(new \DateInterval('P1Y'))
                ->sub(new \DateInterval('P1D'))
        );
        $issuedDateTime = DateUtils::toDate($this->dateOfTest);
        $dateTimeOfTest = DateTimeApiFormat::dateTime($issuedDateTime);

        $completedDateTime = $issuedDateTime->add(new \DateInterval('PT30M'));
        $completedTimeOfTest = DateTimeApiFormat::dateTime($completedDateTime);

        return [
            'startDate' => $dateTimeOfTest,
            'issueDate' => $this->dateOfTest,
            'completedDate' => $completedTimeOfTest,
            'expiryDate' => $expiryDate,
        ];
    }

    public function setNumberOfEvents($numberOfEvents)
    {
        $this->numberOfEvents = $numberOfEvents;
    }

    public function createEventAe()
    {
        for ($i = 0; $i < $this->numberOfEvents; $i++) {
            $id = $this->testSupportHelper->createEvent($this->orgId, 'ae');
        }

        return $id;
    }

    public function createEventSite()
    {
        for ($i = 0; $i < $this->numberOfEvents; $i++) {
            $id = $this->testSupportHelper->createEvent($this->siteId, 'site');
        }

        return $id;
    }

    public function createEventPerson()
    {
        for ($i = 0; $i < $this->numberOfEvents; $i++) {
            $id = $this->testSupportHelper->createEvent($this->testerUserId(), 'person');
        }

        return $id;
    }

    private function createMotRetestParameters()
    {
        return array_merge(
            [
                'mileage' => 2000,
                'outcome' => $this->retestStatus,
            ],
            $this->createMotTestDateSetParam()
        );
    }


    //  ----    User management ----
    private function getUserId(array $person)
    {
        return $person['personId'];
    }

    private function getUsername(array $person)
    {
        return $person['username'];
    }

    private function getName(array $person)
    {
        return $person['firstName'] . " " . $person['surname'];
    }

    private function getSchemeMgt()
    {
        if ($this->userSchemeMgt === null) {
            $this->userSchemeMgt = $this->testSupportHelper->createSchemeManager();
        }

        return $this->userSchemeMgt;
    }

    public function schememgtId()
    {
        return $this->getUserId($this->getSchemeMgt());
    }

    public function schememgtUsername()
    {
        return $this->getUserName($this->getSchemeMgt());
    }

    private function getSchemeUser()
    {
        if ($this->userSchemeUser === null) {
            $this->userSchemeUser = $this->testSupportHelper->createSchemeUser();
        }

        return $this->userSchemeUser;
    }

    public function schemeuserId()
    {
        return $this->getUserId($this->getSchemeUser());
    }

    public function schemeuserUsername()
    {
        return $this->getUserName($this->getSchemeUser());
    }

    public function financeUser()
    {
        return $this->getUsername($this->getFinanceUser());
    }

    public function getAreaOffice()
    {
        if ($this->userAreaOffice1 === null) {
            $this->userAreaOffice1 = $this->testSupportHelper->createAreaOffice1User();
        }

        return $this->userAreaOffice1;
    }

    public function areaOfficeId()
    {
        return $this->getUserId($this->getAreaOffice());
    }

    public function areaOfficeUsername()
    {
        return $this->getUsername($this->getAreaOffice());
    }


    public function getAreaOffice2()
    {
        if ($this->userAreaOffice2 === null) {
            $this->userAreaOffice2 = $this->testSupportHelper->createAreaOffice2User();
        }

        return $this->userAreaOffice2;
    }

    public function areaOffice2Id()
    {
        return $this->getUserId($this->getAreaOffice2());
    }

    public function areaOffice2Username()
    {
        return $this->getUsername($this->getAreaOffice2());
    }


    private function getAed()
    {
        if ($this->userAed === null) {
            $this->userAed = $this->testSupportHelper->createAuthorisedExaminerDelegate(
                $this->areaOffice2Username(),
                null,
                [$this->orgId()]
            );
        }

        return $this->userAed;
    }

    public function aeRef()
    {
        $this->orgId();
        $this->aeRef = $this->orgData['aeRef'];

        return $this->aeRef;
    }

    public function aedId()
    {
        return $this->getUserId($this->getAed());
    }

    public function aedUsername()
    {
        return $this->getUsername($this->getAed());
    }


    private function getAedm()
    {
        if ($this->userAedm === null) {
            $this->userAedm = $this->testSupportHelper->createAuthorisedExaminerDesignatedManagement(
                $this->areaOffice2Username(),
                null,
                [$this->orgId()]
            );
        }

        return $this->userAedm;
    }

    public function aedmId()
    {
        return $this->getUserId($this->getAedm());
    }

    public function aedmUsername()
    {
        return $this->getUsername($this->getAedm());
    }

    private function getVehicleExaminer()
    {
        if ($this->userVehicleExaminer === null) {
            $this->userVehicleExaminer = $this->testSupportHelper->createVehicleExaminer();
        }

        return $this->userVehicleExaminer;
    }

    public function vehicleExaminerId()
    {
        return $this->getUserId($this->getVehicleExaminer());
    }

    public function vehicleExaminerUsername()
    {
        return $this->getUsername($this->getVehicleExaminer());
    }


    private function getDvlaOperative()
    {
        if ($this->userDvlaOper === null) {
            $this->userDvlaOper = $this->testSupportHelper->createDvlaOperative();
        }

        return $this->userDvlaOper;
    }

    public function dvlaOperId()
    {
        return $this->getUserId($this->getDvlaOperative());
    }

    public function dvlaOperUsername()
    {
        return $this->getUsername($this->getDvlaOperative());
    }


    private function getCsco()
    {
        if ($this->userCsco === null) {
            $this->userCsco = $this->testSupportHelper->createCustomerServiceCentreOperative();
        }

        return $this->userCsco;
    }

    public function cscoId()
    {
        return $this->getUserId($this->getCsco());
    }

    public function cscoUsername()
    {
        return $this->getUsername($this->getCsco());
    }


    private function getTester()
    {
        if ($this->userTester === null) {
            $this->userTester = $this->testSupportHelper->createTester(
                $this->schememgtUsername(),
                [$this->siteId()],
                null,
                null,
                $this->createTesterAuthorisationStatus(),
                $this->testerContactEmail
            );
        }

        return $this->userTester;
    }

    private function getInactiveTester()
    {
        if ($this->userTester === null) {
            $this->userTester = $this->testSupportHelper->createInactiveTester(
                $this->schememgtUsername(),
                [$this->siteId()]
            );
        }

        return $this->userTester;
    }

    public function setTesterUsername($testerUsername)
    {
        if ($testerUsername !== '') {
            $this->userTester = ['username' => $testerUsername];
        }
    }


    public function setScoUsername($testerUsername)
    {
        if ($testerUsername !== '') {
            $this->userTester = ['username' => $testerUsername];
        }
    }

    public function testerUserId()
    {
        return $this->getUserId($this->getTester());
    }

    public function testerUsername()
    {
        return $this->getUsername($this->getTester());
    }

    public function inactiveTesterUsername()
    {
        return $this->getUsername($this->getInactiveTester());
    }

    public function testerId()
    {
        return $this->getUserId($this->getTester());
    }

    public function testerPassword()
    {
        return $this->getTester()['password'];
    }

    public function testerName()
    {
        return $this->getName($this->getTester());
    }


    private function getSiteMngr()
    {
        if ($this->userSiteMngr === null) {
            $this->userSiteMngr = $this->testSupportHelper->createSiteManager(
                $this->schememgtUsername(),
                [$this->siteId()]
            );
        }

        return $this->userSiteMngr;
    }

    public function siteMngrId()
    {
        return $this->getUserId($this->getSiteMngr());
    }

    public function siteMngrUsername()
    {
        return $this->getUsername($this->getSiteMngr());
    }


    private function getSiteAdmin()
    {
        if ($this->userSiteAdmin === null) {
            $this->userSiteAdmin = $this->testSupportHelper->createSiteAdmin(
                $this->schememgtUsername(), [$this->siteId()]
            );
        }

        return $this->userSiteAdmin;
    }

    public function siteAdminId()
    {
        return $this->getUserId($this->getSiteAdmin());
    }

    public function siteAdminUsername()
    {
        return $this->getUsername($this->getSiteAdmin());
    }

    private function getFinanceUser()
    {
        if ($this->userFinanceUser === null) {
            $this->userFinanceUser = $this->testSupportHelper->createFinanceUser();
        }

        return $this->userFinanceUser;
    }

    public function financeUserId()
    {
        return $this->getUserId($this->getFinanceUser());
    }

    public function financeUsername()
    {
        return $this->getUsername($this->getFinanceUser());
    }

    /**
     * @param int $securityQuestionId
     */
    public function setSecurityQuestionOneId($securityQuestionId)
    {
        $this->securityQuestionOneId = $securityQuestionId;
    }

    /**
     * @param int $securityQuestionId
     */
    public function setSecurityQuestionTwoId($securityQuestionId)
    {
        $this->securityQuestionTwoId = $securityQuestionId;
    }

    /**
     * @param string $securityQuestionOne
     */
    public function setSecurityQuestionOne($securityQuestionOne)
    {
        $this->securityQuestionOne = $securityQuestionOne;
    }

    /**
     * @param string $securityQuestionTwo
     */
    public function setSecurityQuestionTwo($securityQuestionTwo)
    {
        $this->securityQuestionTwo = $securityQuestionTwo;
    }

    public function createSecurityQuestionOne()
    {
        $this->testSupportHelper->generateSecurityQuestion(
            $this->testerUserId(),
            $this->securityQuestionOneId,
            $this->securityQuestionOne
        );
    }

    public function createSecurityQuestionTwo()
    {
        $this->testSupportHelper->generateSecurityQuestion(
            $this->testerUserId(),
            $this->securityQuestionTwoId,
            $this->securityQuestionTwo
        );
    }

    public function createQuestions()
    {
        $this->createSecurityQuestionOne();
        $this->createSecurityQuestionTwo();
    }

    public function token()
    {
        $data = $this->testSupportHelper->resetPassword(
            $this->schememgtUsername(),
            $this->testerUserId()
        );

        return $data['data']['token'];
    }
}
