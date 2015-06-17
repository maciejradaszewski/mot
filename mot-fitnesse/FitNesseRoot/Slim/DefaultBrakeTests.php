<?php

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\AedmCredentialsProvider;
use DvsaCommon\Enum\BrakeTestTypeCode;

/**
 * Tests that brake test defaults are saved correctly
 */
class DefaultBrakeTests
{
    private $result;
    private $credentialsProvider;
    private $defaultBrakeTestClass1And2;
    private $defaultParkingBrakeTestClass3AndAbove;
    private $defaultServiceBrakeTestClass3AndAbove;
    private $input = [];

    private $brakeTestTypeNameToCodeMap
        = [
            ''                  => null,
            'roller'            => BrakeTestTypeCode::ROLLER,
            'decelerometer'     => BrakeTestTypeCode::DECELEROMETER,
            'floor'             => BrakeTestTypeCode::FLOOR,
            'plate'             => BrakeTestTypeCode::PLATE,
            'gradient'          => BrakeTestTypeCode::GRADIENT,
            'Invalid Test Type' => 'Invalid Test Type',
        ];

    public function execute()
    {
        $this->credentialsProvider = new AedmCredentialsProvider();

        $this->saveNewDefaultTestTypes();
        $this->receiveVehicleTestingStationDetails();
    }

    public function setSiteId($value)
    {
        $this->setInputValue('siteId', $value);
    }

    public function setDefaultBrakeTestClass1And2($value)
    {
        $this->setInputValue('defaultBrakeTestClass1And2', $this->brakeTestTypeNameToCodeMap[$value]);
    }

    public function setDefaultServiceBrakeTestClass3AndAbove($value)
    {
        $this->setInputValue('defaultServiceBrakeTestClass3AndAbove', $this->brakeTestTypeNameToCodeMap[$value]);
    }

    public function setDefaultParkingBrakeTestClass3AndAbove($value)
    {
        $this->setInputValue('defaultParkingBrakeTestClass3AndAbove', $this->brakeTestTypeNameToCodeMap[$value]);
    }

    public function reset()
    {
        $this->input = [];
        $this->result = null;
        $this->defaultBrakeTestClass1And2 = null;
        $this->defaultServiceBrakeTestClass3AndAbove = null;
        $this->defaultParkingBrakeTestClass3AndAbove = null;
    }

    public function success()
    {
        return TestShared::resultIsSuccess($this->result);
    }

    public function valuesWereChangedInDatabase()
    {
        return (
            $this->compareInputToOutputIfExists(
                'defaultBrakeTestClass1And2',
                $this->defaultBrakeTestClass1And2
            )
            && $this->compareInputToOutputIfExists(
                'defaultParkingBrakeTestClass3AndAbove',
                $this->defaultParkingBrakeTestClass3AndAbove
            )
            && $this->compareInputToOutputIfExists(
                'defaultServiceBrakeTestClass3AndAbove',
                $this->defaultServiceBrakeTestClass3AndAbove
            )
        );
    }

    private function saveNewDefaultTestTypes()
    {
        $urlBuilder = (new UrlBuilder())->vehicleTestingStation()->routeParam('id', $this->input['siteId'])
            ->defaultBrakeTests();

        $this->result = TestShared::execCurlFormPutForJsonFromUrlBuilder(
            $this->credentialsProvider,
            $urlBuilder,
            $this->input
        );
    }

    private function receiveVehicleTestingStationDetails()
    {
        $urlBuilder = (new UrlBuilder())->vehicleTestingStation()->routeParam('id', $this->input['siteId']);
        $vtsDetailsResponse = TestShared::execCurlForJsonFromUrlBuilder(
            $this->credentialsProvider,
            $urlBuilder
        );

        if (TestShared::resultIsSuccess($vtsDetailsResponse)) {
            $vtsDetails = $vtsDetailsResponse['data']['vehicleTestingStation'];

            $this->defaultBrakeTestClass1And2 = $vtsDetails['defaultBrakeTestClass1And2'];
            $this->defaultParkingBrakeTestClass3AndAbove = $vtsDetails['defaultParkingBrakeTestClass3AndAbove'];
            $this->defaultServiceBrakeTestClass3AndAbove = $vtsDetails['defaultServiceBrakeTestClass3AndAbove'];
        }
    }

    private function setInputValue($name, $value)
    {
        if (!empty($value)) {
            $this->input[$name] = $value;
        }
    }

    private function compareInputToOutputIfExists($key, $output)
    {
        return isset($this->input[$key]) ? ($this->input[$key] == $output) : true;
    }
}
