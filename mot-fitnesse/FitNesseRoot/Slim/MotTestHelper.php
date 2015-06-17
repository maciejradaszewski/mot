<?php

use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\Enum\ReasonForRejectionTypeName;
use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Testing\Objects\MotTestCreate;
use MotFitnesse\Util\UrlBuilder;
use DvsaCommon\Enum\ColourCode;
use DvsaCommon\Enum\BrakeTestTypeCode;
use DvsaCommon\Enum\ReasonForCancelId;

/**
 * Contains functionality for generation different types of Mot Test or parts of if
 * Class MotTestHelper
 */
class MotTestHelper
{
    const TYPE_MOT_TEST_RETEST = 'RETEST';
    const TYPE_MOT_TEST_NORMAL = 'NORMAL';
    const ONE_TIME_PASSWORD_PASSING = '123456';

    /** @var FitMotApiClient */
    private $client;
    public $credentialsProvider;

    public function __construct(\MotFitnesse\Util\CredentialsProvider $credentials = null)
    {
        if ($credentials == null) {
            $credentials = new \MotFitnesse\Util\Tester1CredentialsProvider();
        }
        $this->client = FitMotApiClient::createForCreds($credentials);
    }

    /**
     * @param MotTestCreate $createObject
     */
    public function createPassedTest(MotTestCreate $createObject)
    {
        $motTestData = $this->createMotTest(
            $createObject->vehicleId,
            $createObject->dvlaVehicleId,
            $createObject->siteId,
            $createObject->primaryColour,
            $createObject->secondaryColour,
            true,
            $createObject->vehicleClass,
            $createObject->fuelType,
            $createObject->isRetest,
            $createObject->motTestType,
            $createObject->originalMotTestNumber
        );
        $motTestNumber = $motTestData['motTestNumber'];

        $this->odometerUpdate(
            $motTestNumber,
            $createObject->odometerResultType,
            $createObject->odometerValue,
            $createObject->odometerUnit
        );

        $this->passBrakeTestResults($motTestNumber);
        $this->changeStatus($motTestNumber, MotTestStatusName::PASSED);

        return $motTestNumber;
    }

    public function createMotTest(
        $vehicleId,
        $dvlaVehicleId,
        $vtsId,
        $primaryColour = ColourCode::ORANGE,
        $secondaryColour = ColourCode::BLACK,
        $hasRegistration = true,
        $vehicleClassCode = VehicleClassCode::CLASS_4,
        $fuelTypeId = 'PE',
        $testType = self::TYPE_MOT_TEST_NORMAL,
        $motTestType = MotTestTypeCode::NORMAL_TEST,
        $motTestNumberOriginal = null
    ) {
        $postArray = [
            'vehicleTestingStationId' => $vtsId,
            'primaryColour'           => $primaryColour,
            'secondaryColour'         => $secondaryColour,
            'hasRegistration'         => $hasRegistration,
            'vehicleClassCode'        => $vehicleClassCode,
            'fuelTypeId'              => $fuelTypeId,
            'oneTimePassword'         => self::ONE_TIME_PASSWORD_PASSING,
            'motTestType'             => $motTestType,
            'motTestNumberOriginal'   => $motTestNumberOriginal
        ];
        $postArray += $vehicleId ? ['vehicleId' => $vehicleId] : ['dvlaVehicleId' => $dvlaVehicleId];

        if (self::TYPE_MOT_TEST_RETEST === $testType) {
            $urlBuilder = (new UrlBuilder())->motRetest();
        } else {
            $urlBuilder = (new UrlBuilder())->motTest();
        }

        return $this->client->post($urlBuilder, $postArray);
    }

    public function getMotTest($motTestNumber)
    {
        return $this->client->get((new UrlBuilder())->motTest()->routeParam('motTestNumber', $motTestNumber));
    }

    public function passBrakeTestResults($motTestNumber)
    {
        return $this->client->post(
            (new UrlBuilder())->motTest()->routeParam('motTestNumber', $motTestNumber)->brakeTestResult(),
            [
                "serviceBrake1TestType"   => BrakeTestTypeCode::DECELEROMETER,
                "parkingBrakeTestType"    => BrakeTestTypeCode::DECELEROMETER,
                "serviceBrake1Efficiency" => 80,
                "parkingBrakeEfficiency"  => 40,
            ]
        );
    }

    public function failBrakeTestResults($motTestNumber)
    {
        return $this->client->post(
            (new UrlBuilder())->motTest()->routeParam('motTestNumber', $motTestNumber)->brakeTestResult(),
            [
                "serviceBrake1TestType"   => BrakeTestTypeCode::DECELEROMETER,
                "parkingBrakeTestType"    => BrakeTestTypeCode::DECELEROMETER,
                "serviceBrake1Efficiency" => 11,
                "parkingBrakeEfficiency"  => 11,
            ]
        );
    }

    public function odometerUpdate($motTestNumber, $odometerType = "OK", $odometerValue = 1000, $odometerUnit = "mi")
    {
        return $this->client->put(
            (new UrlBuilder())->motTest()->routeParam('motTestNumber', $motTestNumber)->odometerReading(),
            [
                "value"      => $odometerValue,
                "unit"       => $odometerUnit,
                "resultType" => $odometerType
            ]
        );
    }

    public function addRfr($motTestNumber, $rfrId = 4, $type = ReasonForRejectionTypeName::FAIL)
    {
        return $this->client->post(
            (new UrlBuilder())->motTest()->routeParam('motTestNumber', $motTestNumber)->reasonsForRejection(),
            [
                'rfrId' => $rfrId,
                'type'  => $type
            ]
        );
    }

    public function abortTest($motTestNumber, $reason = ReasonForCancelId::ABORT)
    {
        $this->changeStatus(
            $motTestNumber,
            MotTestStatusName::ABORTED,
            self::ONE_TIME_PASSWORD_PASSING,
            $reason
        );
    }

    public function abandonTest($testNumber)
    {
        $reasonForAbandonId = current($this->getReasonsForAbandon())['id'];
        $this->changeStatus(
            $testNumber,
            MotTestStatusName::ABORTED,
            self::ONE_TIME_PASSWORD_PASSING,
            $reasonForAbandonId,
            null,
            "Generic abandon comment"
        );
    }

    public function changeStatus(
        $motTestNumber,
        $newStatus,
        $oneTimePassword = null,
        $reasonForCancelId = null,
        $reasonForAbort = null,
        $cancelComment = null
    ) {
        $postArray = [
            "status"          => $newStatus,
            "oneTimePassword" => $oneTimePassword ?: self::ONE_TIME_PASSWORD_PASSING
        ];
        if (!empty($reasonForCancelId)) {
            $postArray['reasonForCancelId'] = intval($reasonForCancelId);
        } elseif (!empty($reasonForAbort)) {
            $postArray['reasonForAbort'] = $reasonForAbort;

        }

        if ($cancelComment) {
            $postArray['cancelComment'] = $cancelComment;
        }

        return $this->client->post(
            (new UrlBuilder())->motTest()->routeParam('motTestNumber', $motTestNumber)->motTestStatus(),
            $postArray
        );
    }

    public function getReasonsForCancel()
    {
        $reasons = $this->client->get((new UrlBuilder())->dataCatalog());

        return $reasons['reasonsForCancel'];
    }

    public function getReasonsForAbandon()
    {
        $reasonsForCancel = $this->getReasonsForCancel();

        return array_filter(
            $reasonsForCancel,
            function ($reason) {
                return $reason["abandoned"];
            }
        );
    }

    public function getMotTestHistory($vehicleId)
    {
        $result = $this->client->get((new UrlBuilder())->testHistory($vehicleId));

        return $result;
    }
}
