<?php

use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\CredentialsProvider;
use DvsaCommon\Enum\ColourCode;

class Vm4502AbandonMotTest
{
    private $abandoner;
    private $creator;
    private $testType;

    private $usersMap;
    /**  @var \ApiErrorException $exception */
    private $exception;

    private $authorisedExaminer;
    private $vehicleTestingStation;
    private $vehicleId;

    private $auxTesterCreds;


    public function setTestType($code)
    {
        $this->testType = $code;
    }

    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    public function setAbandoner($abandoner)
    {
        $this->abandoner = $abandoner;
    }

    public function beginTable()
    {
        $this->createFixtures();
    }

    public function execute()
    {
        $creatorCreds = CredentialsProvider::fromArray($this->usersMap[$this->creator]);
        $abandonerCreds = CredentialsProvider::fromArray($this->usersMap[$this->abandoner]);

        try {
            $this->generateVehicle();
            $testNumber = $this->createTargetTest($creatorCreds);
            (new MotTestHelper($abandonerCreds))->abandonTest($testNumber);
        } catch (ApiErrorException $ex) {
            $this->exception = $ex;
            (new MotTestHelper($creatorCreds))->abortTest($testNumber);
        }
    }

    public function reset()
    {
        $this->exception = null;
    }

    private function createTargetTest(CredentialsProvider $creatorCreds)
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
            return $this->createTest($creatorCreds, $this->testType, $origTestNo);
        } else {
            $origTestNo = null;
            /** if a retest, first create normal test */
            if ($this->testType === MotTestTypeCode::RE_TEST) {
                $origTestNo = $this->createTest($creatorCreds, MotTestTypeCode::NORMAL_TEST);
                $this->failTest($creatorCreds, $origTestNo);
            }

            return $this->createTest($creatorCreds, $this->testType, $origTestNo);
        }
    }

    private function createTest(CredentialsProvider $creds, $testType, $motTestNumberOriginal = null)
    {
        $motTestHelper = new MotTestHelper($creds);
        return $motTestHelper->createMotTest(
            $this->vehicleId,
            null,
            $this->vehicleTestingStation['id'],
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

    public function success()
    {
        return !$this->exception;
    }

    public function errorMessages()
    {
        return $this->success() ? '' : $this->exception->getMessage();
    }

    private function createFixtures()
    {
        $testSupportHelper = new TestSupportHelper();
        $schmMgr = $testSupportHelper->createSchemeManager();

        $this->authorisedExaminer = $testSupportHelper->createAuthorisedExaminer(
            $testSupportHelper->createAreaOffice1User()['username']
        );

        $this->vehicleTestingStation = $testSupportHelper->createVehicleTestingStation(
            $testSupportHelper->createAreaOffice1User()['username'],
            $this->authorisedExaminer['id']
        );
        $vtsId = $this->vehicleTestingStation['id'];

        $ve1 = $testSupportHelper->createVehicleExaminer();
        $ve2 = $testSupportHelper->createVehicleExaminer();

        $tester1 = $testSupportHelper->createTester(
            $schmMgr['username'],
            [$vtsId]
        );

        $tester2 = $testSupportHelper->createTester(
            $schmMgr['username'],
            [$vtsId]
        );

        $this->usersMap = ['T1' => $tester1, 'T2' => $tester2, 'VE1' => $ve1, 'VE2' => $ve2];
        $this->auxTesterCreds = CredentialsProvider::fromArray($tester1);
    }

    private function generateVehicle()
    {
        $client = FitMotApiClient::createForCreds($this->auxTesterCreds);
        $vehicleTestHelper = new VehicleTestHelper($client);
        $this->vehicleId = $vehicleTestHelper->generateVehicle();
    }
}
