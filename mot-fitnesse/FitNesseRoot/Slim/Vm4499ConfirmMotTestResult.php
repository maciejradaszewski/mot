 <?php

use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\CredentialsProvider;
use DvsaCommon\Enum\ColourCode;

class Vm4499ConfirmMotTestResult
{
    /** @var  TestSupportHelper */
    private $testSupportHelper;
    private $testType;
    private $vts;
    private $vehicleId;
    private $usersMap;
    private $auxTesterCreds;
    private $response;
    private $startingUser;
    private $confirmingUser;
    private $suspendAuthorisation;
    private $confirmedTo;

    public function beginTable()
    {
        $this->createFixtures();
    }

    public function execute()
    {
        $creatorCreds = CredentialsProvider::fromArray($this->usersMap[$this->startingUser]);
        $confirmerCreds = CredentialsProvider::fromArray($this->usersMap[$this->confirmingUser]);

        $this->generateVehicle();

        try {
            $testNumber = $this->createTargetTest($creatorCreds, $this->confirmedTo);
            $this->suspendTester($this->usersMap[$this->confirmingUser]['personId']);
            $this->confirmTest($confirmerCreds, $testNumber, $this->confirmedTo);

            $this->response = 'Confirmed';
        } catch (ApiErrorException $e) {
            if (!$this->suspendAuthorisation) {
                (new MotTestHelper($creatorCreds))->abortTest($testNumber);
            }
            $this->response = $e->getMessage();
        }
    }

    public function reset()
    {
        $this->suspendAuthorisation = false;
    }

    public function setUserWhomTryToConfirmTheTest($confirmingUser)
    {
        $this->confirmingUser = $confirmingUser;
    }

    public function setShouldBeInactive($create)
    {
        if (strtolower($create) === 'yes') {
            $this->suspendAuthorisation = true;
        }
    }

    public function setConfirmedTo($confirmedTo)
    {
        $this->confirmedTo = $confirmedTo;
    }

    public function response()
    {
        return $this->response;
    }

    private function createFixtures()
    {
        $this->testSupportHelper = new TestSupportHelper();
        $schmMgr = $this->testSupportHelper->createSchemeManager($this->signUserName('motSchemeUser'));
        $areaOffice1Username = $this->testSupportHelper->createAreaOffice1User()['username'];
        $areaOffice2Username = $this->testSupportHelper->createAreaOffice2User()['username'];

        $ae = $this->testSupportHelper->createAuthorisedExaminer(
            $areaOffice1Username,
            $this->signUserName('ae')
        );
        $this->vts =$this->testSupportHelper->createVehicleTestingStation(
            $areaOffice1Username,
            $ae['id'],
            $this->signUserName('vts')
        );

        $aedm = $this->testSupportHelper->createAuthorisedExaminerDesignatedManagement(
            $areaOffice2Username,
            $this->signUserName('aedm'),
            [$ae['id']]
        );

        $aed = $this->testSupportHelper->createAuthorisedExaminerDelegate(
            $areaOffice2Username,
            $this->signUserName('aed'),
            [$ae['id']]
        );

        $ve1 = $this->testSupportHelper->createVehicleExaminer(
            $schmMgr['username'],
            $this->signUserName('ve-A')
        );

        $ve2 = $this->testSupportHelper->createVehicleExaminer(
            $schmMgr['username'],
            $this->signUserName('ve-B')
        );

        $siteManager = $this->testSupportHelper->createSiteManager(
            $schmMgr['username'],
            [$this->vts['id']],
            $this->signUserName('vtsSiteManager')
        );

        $tester1 = $this->testSupportHelper->createTester(
            $schmMgr['username'],
            [$this->vts['id']],
            $this->signUserName('tester-A')
        );

        $tester2 = $this->testSupportHelper->createTester(
            $schmMgr['username'],
            [$this->vts['id']],
            $this->signUserName('tester-B')
        );

        $tester3 = $this->testSupportHelper->createTester(
            $schmMgr['username'],
            [$this->vts['id']],
            $this->signUserName('tester-C')
        );

        $this->auxTesterCreds = CredentialsProvider::fromArray($tester2);

        $this->usersMap = [
            'T1'   => $tester1,
            'T2'   => $tester2,
            'T3'   => $tester3,
            'VE1'  => $ve1,
            'VE2'  => $ve2,
            'AEDM' => $aedm,
            'AED'  => $aed,
            'SM'   => $siteManager
        ];
    }

    public function setTestType($testType)
    {
        $this->testType = $testType;
    }

    public function setUserWhomStartsATest($startingUser)
    {
        $this->startingUser = $startingUser;
    }

    private function signUserName($username)
    {
        return 'vm-4499-' . $username;
    }

    private function generateVehicle()
    {
        $client = FitMotApiClient::createForCreds($this->auxTesterCreds);
        $vehicleTestHelper = new VehicleTestHelper($client);
        $this->vehicleId = $vehicleTestHelper->generateVehicle();
    }


    private function createTargetTest(CredentialsProvider $creatorCreds, $targetResult)
    {
        // VE tests
        if (in_array(
            $this->testType,
            [
                MotTestTypeCode::TARGETED_REINSPECTION,
                MotTestTypeCode::MOT_COMPLIANCE_SURVEY,
                MotTestTypeCode::INVERTED_APPEAL,
                MotTestTypeCode::STATUTORY_APPEAL,
            ]
        )) {
            /**
             * Create a normal test with some tester and fail it to create Reinspection one that is linked
             * to it
             */
            $origTestNo = $this->createTest($this->auxTesterCreds, MotTestTypeCode::NORMAL_TEST);
            $this->failTest($this->auxTesterCreds, $origTestNo);
            /** create proper VE test using it of a normal test*/
            $testNumber = $this->createTest($creatorCreds, $this->testType, $origTestNo);
        } else {
            $origTestNo = null;
            /** if a retest, first create normal test */
            if ($this->testType === MotTestTypeCode::RE_TEST) {
                $origTestNo = $this->createTest($creatorCreds, MotTestTypeCode::NORMAL_TEST);
                $this->failTest($creatorCreds, $origTestNo);
            }

            $testNumber = $this->createTest($creatorCreds, $this->testType, $origTestNo);
        }

        // finish test without confirming
        $motTestHelper = new MotTestHelper($creatorCreds);
        $motTestHelper->odometerUpdate($testNumber);
        if ($targetResult == MotTestStatusName::PASSED) {
            $motTestHelper->passBrakeTestResults($testNumber);
        } else {
            if ($targetResult == MotTestStatusName::FAILED) {
                $motTestHelper->failBrakeTestResults($testNumber);
            } else {
                throw new \Exception('unknown status');
            }
        }
        return $testNumber;
    }

    private function createTest(CredentialsProvider $creds, $testType, $motTestNumberOriginal = null)
    {
        $motTestHelper = new MotTestHelper($creds);
        return $motTestHelper->createMotTest(
            $this->vehicleId,
            null,
            $this->vts['id'],
            ColourCode::BLACK,
            ColourCode::NOT_STATED,
            true,
            VehicleClassCode::CLASS_4,
            'PE',
            'NORMAL',
            $testType,
            $motTestNumberOriginal
        )['motTestNumber'];
    }

    private function failTest(CredentialsProvider $creds, $motTestNumber)
    {
        $motTestHelper = new MotTestHelper($creds);
        $motTestHelper->failBrakeTestResults($motTestNumber);
        $motTestHelper->odometerUpdate($motTestNumber);
        $motTestHelper->changeStatus(
            $motTestNumber,
            MotTestStatusName::FAILED
        );
    }

    private function confirmTest(CredentialsProvider $creds, $motTestNumber, $status)
    {
        $motTestHelper = new MotTestHelper($creds);
        $motTestHelper->changeStatus(
            $motTestNumber,
            $status
        );
    }

    private function suspendTester($personId)
    {
        if ($this->suspendAuthorisation) {
            $this->testSupportHelper->suspendTester($personId);
        }
    }
}
