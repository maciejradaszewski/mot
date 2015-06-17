<?php

require_once 'configure_autoload.php';
use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

/**
 * Class Vm16SlotsAvailableToTest
 *
 * used to carry out the fitnesse tests needed for various scenarios of starting a test with and without slots.
 */
class Vm16SlotsAvailableToTest
{
    private $userId;
    private $userType;

    protected $vehicleTestingStationId;
    public $username;
    public $password = TestShared::PASSWORD;
    private $vehicleId;
    private $primaryColour;
    private $secondaryColour;
    private $hasRegistration = 'true';
    private $error = false;
    private $testType;

    const INSIGNIFICANT_COLOUR = "B";

    /** @var TestSupportHelper $testSupportHelper */
    private $testSupportHelper;

    private $schmUserUsername;

    private $aeSlots;

    public function beginTable()
    {
        $this->testSupportHelper = new TestSupportHelper();
        $response = $this->testSupportHelper->createSchemeManager();
        $this->schmUserUsername = $response['username'];

        if (!$this->schmUserUsername) {
            throw new \Exception("Response: " . print_r($response, true));
        }
    }

    public function setAeSlots($slots)
    {
        $this->aeSlots = $slots;
    }

    public function errorMessage()
    {

        $aeId = $this->testSupportHelper->createAuthorisedExaminer(
            $this->testSupportHelper->createAreaOffice1User()['username'],
            'vm16',
            $this->aeSlots
        )['id'];
        $vtsId = $this->testSupportHelper->createVehicleTestingStation(
            $this->testSupportHelper->createAreaOffice1User()['username'],
            $aeId
        )['id'];

        $tester = $this->testSupportHelper->createTester($this->schmUserUsername, [$vtsId]);
        $newTesterCreds = new CredentialsProvider($tester['username'], $tester['password']);

        $this->vehicleId = (new VehicleTestHelper(FitMotApiClient::createForCreds($newTesterCreds)))->generateVehicle();

        $motHelper = new MotTestHelper($newTesterCreds);

        if ($this->testType == 'RETEST') {
            $this->createFailedTest($motHelper, $vtsId, 'NORMAL');

            // Burn another slot
            $vehicleId = (new VehicleTestHelper(FitMotApiClient::createForCreds($newTesterCreds)))->generateVehicle();
            $createObject = (new \MotFitnesse\Testing\Objects\MotTestCreate())->vehicleId($vehicleId)
                ->siteId($vtsId)
                ->primaryColour(self::INSIGNIFICANT_COLOUR)
                ->secondaryColour(self::INSIGNIFICANT_COLOUR)
                ->odometerValue(55555);
            $motHelper->createPassedTest($createObject);
        }

        try {
            $this->createFailedTest($motHelper, $vtsId, $this->testType);

        } catch (\ApiErrorException $ex) {
            $this->error = true;

            return $ex->getMessage();
        }

        return '';
    }

    public function setTestType($value)
    {
        if ($value === MotTestHelper::TYPE_MOT_TEST_RETEST || $value == MotTestHelper::TYPE_MOT_TEST_NORMAL) {
            $this->testType = $value;
        } else {
            throw new exception("testType [" . $value . "] not recognised");
        }
    }

    public function canUserPerformTest()
    {
        return $this->error ? 'NO' : 'YES';
    }

    /**
     * @param MotTestHelper $motHelper
     * @param               $vtsId
     * @param               $testType
     */
    protected function createFailedTest(MotTestHelper $motHelper, $vtsId, $testType)
    {
        $motTestData = $motHelper->createMotTest(
            $this->vehicleId,
            null,
            $vtsId,
            $this->primaryColour === "null" ? null : self::INSIGNIFICANT_COLOUR,
            $this->secondaryColour === "null" ? null : self::INSIGNIFICANT_COLOUR,
            $this->hasRegistration,
            VehicleClassCode::CLASS_4,
            'PE',
            $testType
        );

        $motTestNumber = $motTestData['motTestNumber'];

        $motHelper->odometerUpdate($motTestNumber);
        $motHelper->passBrakeTestResults($motTestNumber);
        $motHelper->addRfr($motTestNumber, 508);
        $motHelper->changeStatus($motTestNumber, "FAILED");
    }
}
