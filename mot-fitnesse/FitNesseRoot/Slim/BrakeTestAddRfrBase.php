<?php

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;
use DvsaCommon\Enum\ColourCode;

abstract class BrakeTestAddRfrBase
{
    const ERROR_TEXT = 'ERROR';
    const PASS_TEXT = 'PASS';
    const FAIL_TEXT = 'FAIL';

    protected $username = TestShared::USERNAME_TESTER1;
    protected $password = TestShared::PASSWORD;

    protected $brakeTestForm = [];
    protected $motTestBeforSubmit = null;
    protected $hasErrors = false;
    protected $currentMotTestNumber = null;
    protected $fails = [];
    protected $brakeTestResult = [];
    private $error;
    private $addedRfr;

    const INSIGNIFICANT_COLOUR = "B";

    protected $vehicleId;
    protected $vehicleClassCode;
    /** @var  MotTestHelper */
    protected $motTestHelper;
    private $siteId;

    public function __construct($testerUsername, $siteId)
    {
        $this->username = $testerUsername;
        $this->siteId = $siteId;
    }

    public function beginTable()
    {
        $creds = new CredentialsProvider($this->username, $this->password);
        $vehicleTestHelper = new VehicleTestHelper(FitMotApiClient::createForCreds($creds));
        $vehicleId = $vehicleTestHelper->generateVehicle(['testClass' => $this->vehicleClassCode]);
        $this->motTestHelper = new MotTestHelper($creds);

        try {
            $currentMotTest = $this->motTestHelper->createMotTest(
                $vehicleId,
                null,
                $this->siteId,
                ColourCode::BLACK,
                ColourCode::NOT_STATED,
                true,
                $this->vehicleClassCode,
                'PE',
                'NORMAL'
            );

            $this->currentMotTestNumber = $currentMotTest['motTestNumber'];

            $this->motTestBeforSubmit = $this->motTestHelper->getMotTest($this->currentMotTestNumber);
        } catch (ApiErrorException $ex) {
            $this->error = true;

            return $ex->getMessage();
        }
    }

    public function endTable()
    {
        $this->motTestHelper->abortTest($this->currentMotTestNumber);
    }

    protected function beforeExecute()
    {

    }

    public function execute()
    {
        $this->beforeExecute();
        $brakeTestUrl = (new UrlBuilder())->motTest()
            ->routeParam('motTestNumber', $this->currentMotTestNumber)->brakeTestResult();
        try {
            FitMotApiClient::create($this->username, $this->password)
                ->post($brakeTestUrl, $this->brakeTestForm);
            $result = FitMotApiClient::create($this->username, $this->password)
                ->get($brakeTestUrl);

            $this->brakeTestResult = $result['brakeTestResult'];
            $reasonsForRejection = $result['reasonsForRejection'];
            $fails = array_key_exists('FAIL', $reasonsForRejection) ? $reasonsForRejection['FAIL'] : [];
            $this->fails = $fails;
            if (array_key_exists('FAIL', $this->motTestBeforSubmit['reasonsForRejection'])) {
                $oldFails = $this->motTestBeforSubmit['reasonsForRejection']['FAIL'];
            } else {
                $oldFails = [];
            }

            $newFails = array_udiff($fails, $oldFails, ['BrakeTestAddRfrBase', 'compareRfrs']);
            $latestFail = current($newFails);
            $this->addedRfr = $latestFail != null ?
                $latestFail['name'] . ' ' . $latestFail['failureText'] . ' [' . $latestFail['inspectionManualReference'] . ']'
                : 'none';

        } catch (\ApiErrorException $e) {
            $this->hasErrors = true;
        }

    }

    public function setUsername($value)
    {
        $this->username = $value;
    }

    public function addedRfr()
    {
        return $this->addedRfr;
    }

    public function rfrCount()
    {
        return count($this->fails);
    }

    public static function compareRfrs($rfr1, $rfr2)
    {
        if ($rfr1['id'] < $rfr2['id']) {
            return -1;
        } elseif ($rfr1['id'] > $rfr2['id']) {
            return 1;
        } else {
            return 0;
        }
    }

    protected function valueOrError(&$val)
    {
        return $this->hasErrors ? self::ERROR_TEXT : $val;
    }

    protected static function passFailOrNa(&$value)
    {
        return !isset($value) ? "N/A" : ($value ? self::PASS_TEXT : self::FAIL_TEXT);
    }

    protected function passFailOrError(&$value)
    {
        if ($this->hasErrors) {
            return self::ERROR_TEXT;
        }

        return $value ? self::PASS_TEXT : self::FAIL_TEXT;
    }
}
