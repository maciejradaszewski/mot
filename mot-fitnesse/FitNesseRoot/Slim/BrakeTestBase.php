<?php

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;
use DvsaCommon\Enum\ColourCode;

abstract class BrakeTestBase
{
    const ERROR_TEXT = 'ERROR';
    const PASS_TEXT = 'PASS';
    const FAIL_TEXT = 'FAIL';
    const LOCK_TEXT = 'LOCK';
    const YES = 'YES';

    protected $brakeTestForm = [];
    protected $brakeTestResult = null;
    protected $hasErrors = false;
    protected $errorMessage;
    protected $motTestNumber = null;
    protected $vehicleId;
    protected $vehicleClassCode;
    /** @var  MotTestHelper */
    protected $creatorMotTestHelper;
    private $vehicleFirstUseAfterFirstJan1968;
    protected $savedCorrectlyResponse;

    protected $password = TestShared::PASSWORD;
    private $creatorUsername = TestShared::USERNAME_TESTER1;
    private $modifierUsername = TestShared::USERNAME_TESTER1;
    private $siteId;

    public function __construct($creatorUsername, $siteId)
    {
        $this->creatorUsername = $creatorUsername;
        $this->modifierUsername = $creatorUsername;
        $this->siteId = $siteId;
    }

    public function beginTable()
    {
        $creds = new CredentialsProvider($this->creatorUsername, $this->password);
        $vehicleTestHelper = new VehicleTestHelper(FitMotApiClient::createForCreds($creds));
        $vehicleId = $this->resolveVehicleId($vehicleTestHelper);

        $this->creatorMotTestHelper = new MotTestHelper($creds);
        try {
            $currentMotTest = $this->creatorMotTestHelper->createMotTest(
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

            $this->motTestNumber = $currentMotTest['motTestNumber'];

        } catch (ApiErrorException $ex) {
            $this->hasErrors = true;
            return $ex->getMessage();
        }
    }

    public function endTable()
    {
        $this->creatorMotTestHelper->abortTest($this->motTestNumber);
    }

    protected function resolveVehicleId(VehicleTestHelper $vehicleTestHelper)
    {
        $vehicleData = ['testClass' => $this->vehicleClassCode];
        if ($this->vehicleFirstUseAfterFirstJan1968 === true) {
            $vehicleData = array_merge($vehicleData, ['dateOfFirstUse' => '2011-07-01']);
        }
        $vehicleId = $vehicleTestHelper->generateVehicle($vehicleData);

        return $vehicleId;
    }

    public function reset()
    {
        $this->hasErrors = false;
        $this->errorMessage = null;
        $this->brakeTestResult = null;
        $this->brakeTestForm = [];
    }

    public function beforeExecute()
    {

    }

    protected function afterExecute()
    {

    }

    public function execute()
    {
        $this->beforeExecute();
        $creds = new CredentialsProvider($this->modifierUsername, $this->password);
        $url = (new UrlBuilder())->motTest()->routeParam('motTestNumber', $this->motTestNumber)->brakeTestResult();

        try {
            FitMotApiClient::createForCreds($creds)->post($url, $this->brakeTestForm);
            $result = FitMotApiClient::createForCreds($creds)->get($url);
            $this->brakeTestResult = $result['brakeTestResult'];
            $fullResponse = [];
            $fullResponse['data'] = $result;
            $this->savedCorrectlyResponse = $this->checkSavedCorrectly($fullResponse);
            $this->afterExecute();
        } catch (ApiErrorException $e) {
            $this->hasErrors = true;
            $this->errorMessage = $e->getDisplayMessage();
            $this->savedCorrectlyResponse = 'N/A';
        }
    }

    public function setFirstUseAfterFirstJan1968($value)
    {
        $this->vehicleFirstUseAfterFirstJan1968 = $value === self::YES;
    }

    public function setCreatorUsername($value)
    {
        $this->creatorUsername = $value;
    }

    public function setModifierUsername($value)
    {
        $this->modifierUsername = $value;
    }

    public function generalPass()
    {
        if (!isset($this->brakeTestResult['generalPass'])) {
            return $this->errorMessage ?: self::ERROR_TEXT;
        }
        return $this->brakeTestResult['generalPass'] ? self::PASS_TEXT : self::FAIL_TEXT;
    }


    public function checkSavedCorrectly($response)
    {
        return (new \MotFitnesse\Testing\MotTest\MotTestRetrieveCheckingHelper($this->motTestNumber))
            ->savedCorrectly($this->brakeTestForm, $response, 'brakeTestResult');
    }

    public function savedCorrectly()
    {
        return $this->savedCorrectlyResponse;
    }


    protected static function textNullToNull($val)
    {
        return $val === 'NULL' ? null : $val;
    }

    protected function lockToBool($lock)
    {
        $maybeNull = self::textNullToNull($lock);
        return !is_null($maybeNull) ? ($lock == self::LOCK_TEXT) : $maybeNull;
    }

    protected function stringToBool($string)
    {
        return filter_var($string, FILTER_VALIDATE_BOOLEAN);
    }

    protected function valueOrError(&$value)
    {
        return $this->hasErrors ? self::ERROR_TEXT : $value;
    }

    protected function valueNaOrError(&$value)
    {
        return $this->hasErrors ? self::ERROR_TEXT : (isset($value) ? $value : 'N/A');
    }

    protected function passFailOrError(&$value)
    {
        if ($this->hasErrors) {
            return self::ERROR_TEXT;
        }
        return $value ? self::PASS_TEXT : self::FAIL_TEXT;
    }

    protected function passFailNaOrError(&$value)
    {
        return $this->hasErrors ? self::ERROR_TEXT
            : (isset($value) ? ($value ? self::PASS_TEXT : self::FAIL_TEXT) : 'N/A');

    }

    protected function convertToPassOrFail($value)
    {
        if ($value === true) {
            return self::PASS_TEXT;
        } else {
            if ($value === false) {
                return self::FAIL_TEXT;
            } else {
                return $value;
            }
        }
    }

    protected function convertFromPassOrFail($value)
    {
        if ($value === self::PASS_TEXT) {
            return true;
        }
        if ($value === self::FAIL_TEXT) {
            return false;
        }
        return null;
    }
}
