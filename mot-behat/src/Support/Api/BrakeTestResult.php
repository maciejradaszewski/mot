<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\Request;

class BrakeTestResult extends MotApi
{
    const PATH = 'mot-test/{mot_test_number}/brake-test-result';

    public function addBrakeTestRollerClass3To7($token, $motNumber)
    {
        $body = json_encode([
            'serviceBrake1Data' => [
                'effortNearsideAxle1' => 100,
                'effortOffsideAxle1' => 100,
                'lockNearsideAxle1' => null,
                'lockOffsideAxle1' => null,
                'effortNearsideAxle2' => 300,
                'effortOffsideAxle2' => 300,
                'lockNearsideAxle2' => null,
                'lockOffsideAxle2' => null,
            ],
            'parkingBrakeEffortSingle' => null,
            'parkingBrakeLockSingle' => null,
            'parkingBrakeEffortNearside' => 100,
            'parkingBrakeEffortOffside' => 100,
            'parkingBrakeLockNearside' => null,
            'parkingBrakeLockOffside' => null,
            'serviceBrake1TestType' => 'rollr',
            'serviceBrake2TestType' => null,
            'parkingBrakeTestType' => 'rollr',
            'weightType' => 'vsi',
            'vehicleWeight' => 1000,
            'brakeLineType' => 'dual',
            'numberOfAxles' => 2,
            'parkingBrakeNumberOfAxles' => 1,
            'positionOfSingleWheel' => null,
            'parkingBrakeWheelsCount' => null,
            'serviceBrakeControlsCount' => null,
            'vehiclePurposeType' => null,
            'serviceBrakeIsSingleLine' => false,
            'weightIsUnladen' => false,
            'isCommercialVehicle' => false,

        ]);

        return $this->client->request(new Request(
            'POST',
            str_replace('{mot_test_number}', $motNumber, self::PATH),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }

    public function addBrakeTestRollerClass3To7WithCustomData($token, $motNumber, RollerBrakeTestClass3To7 $rollerObject)
    {
        $body = json_encode([
            'serviceBrake1Data' => [
                'effortNearsideAxle1' => $rollerObject->getEffortNearsideAxle1(),
                'effortOffsideAxle1' => $rollerObject->getEffortOffsideAxle1(),
                'lockNearsideAxle1' => $rollerObject->getLockNearsideAxle1(),
                'lockOffsideAxle1' => $rollerObject->getLockOffsideAxle1(),
                'effortNearsideAxle2' => $rollerObject->getEffortNearsideAxle2(),
                'effortOffsideAxle2' => $rollerObject->getEffortOffsideAxle2(),
                'lockNearsideAxle2' => $rollerObject->getLockNearsideAxle1(),
                'lockOffsideAxle2' => $rollerObject->getLockOffsideAxle2(),
            ],
            'parkingBrakeEffortSingle' => $rollerObject->getParkingBrakeEffortSingle(),
            'parkingBrakeLockSingle' => $rollerObject->getParkingBrakeLockSingle(),
            'parkingBrakeEffortNearside' => $rollerObject->getParkingBrakeEffortNearside(),
            'parkingBrakeEffortOffside' => $rollerObject->getParkingBrakeEffortOffside(),
            'parkingBrakeLockNearside' => $rollerObject->getParkingBrakeLockNearside(),
            'parkingBrakeLockOffside' => $rollerObject->getParkingBrakeLockOffside(),
            'serviceBrake1TestType' => $rollerObject->getServiceBrake1TestType(),
//            'serviceBrake2TestType' => $rollerObject->getServiceBrake1TestType(),
            'parkingBrakeTestType' => $rollerObject->getParkingBrakeTestType(),
            'weightType' => $rollerObject->getWeightType(),
            'vehicleWeight' => $rollerObject->getVehicleWeight(),
            'brakeLineType' => $rollerObject->getBrakeLineType(),
            'numberOfAxles' => $rollerObject->getNumberOfAxles(),
            'parkingBrakeNumberOfAxles' => $rollerObject->getParkingBrakeNumberOfAxles(),
            'positionOfSingleWheel' => $rollerObject->getPositionOfSingleWheel(),
            'parkingBrakeWheelsCount' => $rollerObject->getParkingBrakeWheelsCount(),
            'serviceBrakeControlsCount' => $rollerObject->getServiceBrakeControlsCount(),
            'vehiclePurposeType' => $rollerObject->getVehiclePurposeType(),
            'serviceBrakeIsSingleLine' => $rollerObject->getServiceBrakeIsSingleLine(),
            'weightIsUnladen' => $rollerObject->getWeightIsUnladen(),
            'isCommercialVehicle' => $rollerObject->getIsCommercialVehicle(),

        ]);

        return $this->client->request(new Request(
            'POST',
            str_replace("{mot_test_number}", $motNumber, self::PATH),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }

    public function addBrakeTestForRollerClass1To2($token, $motNumber)
    {
        $body = json_encode([
            'control1EffortFront' => 100,
            'control1EffortRear' => 100,
            'control1EffortSidecar' => null,
            'control2EffortFront' => 100,
            'control2EffortRear' => 100,
            'control2EffortSidecar' => null,
            'control1LockFront' => false,
            'control1LockRear' => false,
            'control2LockFront' => false,
            'control2LockRear' => false,
            'brakeTestType' => 'ROLR',
            'vehicleWeightFront' => 100,
            'vehicleWeightRear' => 100,
            'riderWeight' => 100,
            'isSidecarAttached' => 0,
            'sidecarWeight' => null,
        ]);

        return $this->client->request(new Request(
            'POST',
            str_replace('{mot_test_number}', $motNumber, self::PATH),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }

    public function addBrakeTestForRollerClass1To2WithCustomData($token, $motNumber, $dataMap)
    {
        $body = json_encode([
            'control1EffortFront' => $dataMap['control1EffortFront'],
            'control1EffortRear' => $dataMap['control1EffortRear'],
            'control1EffortSidecar' => $dataMap['control1EffortSidecar'],
            'control2EffortFront' => $dataMap['control2EffortFront'],
            'control2EffortRear' => $dataMap['control2EffortRear'],
            'control2EffortSidecar' => $dataMap['control2EffortSidecar'],
            'control1LockFront' => $dataMap['control1LockFront'] == "true" ? true : false,
            'control1LockRear' => $dataMap['control1LockRear']   == "true" ? true : false,
            'control2LockFront' => $dataMap['control2LockFront'] == "true" ? true : false,
            'control2LockRear' => $dataMap['control2LockRear']   == "true" ? true : false,
            'brakeTestType' => 'ROLLR',
            'vehicleWeightFront' => $dataMap['vehicleWeightFront'],
            'vehicleWeightRear' => $dataMap['vehicleWeightRear'],
            'riderWeight' => $dataMap['riderWeight'],
            'isSidecarAttached' => $dataMap['isSideCarAttached'],
            'sidecarWeight' => $dataMap['isSideCarAttached'] == "1" ? $dataMap['sidecarWeight'] : null
        ]);

        return $this->client->request(new Request(
            'POST',
            str_replace('{mot_test_number}', $motNumber, self::PATH),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }


    public function addBrakeTestDecelerometerClass3To7($token, $motNumber)
    {
        $body = json_encode([
            'serviceBrake1Efficiency' => 80,
            'parkingBrakeEfficiency' => 75,
            'serviceBrake1TestType' => 'DECEL',
            'serviceBrake2TestType' => null,
            'parkingBrakeTestType' => 'DECEL',
            'weightType' => null, 'vehicleWeight' => null,
            'brakeLineType' => 'dual',
            'numberOfAxles' => 2,
            'parkingBrakeNumberOfAxles' => 1,
            'positionOfSingleWheel' => null,
            'parkingBrakeWheelsCount' => null,
            'serviceBrakeControlsCount' => null,
            'vehiclePurposeType' => null,
            'serviceBrakeIsSingleLine' => false,
            'isCommercialVehicle' => false, ]);

        return $this->client->request(new Request(
            'POST',
            str_replace('{mot_test_number}', $motNumber, self::PATH),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }

    public function addBrakeTestDecelerometerClass3To7WithCustomData($token, $motNumber, $data)
    {
        $body = json_encode([
            'serviceBrake1Efficiency' => $data['serviceBrake1Efficiency'],
            'parkingBrakeEfficiency' => $data['parkingBrakeEfficiency'],
            'serviceBrake1TestType' => 'DECEL',
            'serviceBrake2TestType' => null,
            'parkingBrakeTestType' => 'DECEL',
            'weightType' => null, 'vehicleWeight' => null,
            'brakeLineType' => 'dual',
            'numberOfAxles' => 2,
            'parkingBrakeNumberOfAxles' => 1,
            'positionOfSingleWheel' => null,
            'parkingBrakeWheelsCount' => null,
            'serviceBrakeControlsCount' => null,
            'vehiclePurposeType' => null,
            'serviceBrakeIsSingleLine' => false,
            'isCommercialVehicle' => $data['isCommercialVehicle'],
        ]);

        return $this->client->request(new Request(
            'POST',
            str_replace('{mot_test_number}', $motNumber, self::PATH),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }

    public function addBrakeTestDecelerometerClass1To2($token, $motNumber, $control1BrakeEfficiency = 66, $control2BrakeEfficiency = 66)
    {
        $body = json_encode([
            'control1EffortFront' => null,
            'control1EffortRear' => null,
            'control1EffortSidecar' => null,
            'control2EffortFront' => null,
            'control2EffortRear' => null,
            'control2EffortSidecar' => null,
            'brakeTestType' => 'DECEL',
            'vehicleWeightFront' => null,
            'vehicleWeightRear' => null,
            'riderWeight' => null,
            'isSidecarAttached' => 0,
            'sidecarWeight' => null,
            'control1BrakeEfficiency' => $control1BrakeEfficiency,
            'control2BrakeEfficiency' => $control2BrakeEfficiency,
        ]);

        return $this->client->request(new Request(
            'POST',
            str_replace('{mot_test_number}', $motNumber, self::PATH),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }

    public function addBrakeTestDecelerometerClass1To2WithCustomData($token, $motNumber, $dataMap)
    {
        $body = json_encode([
            'control1EffortFront' => null,
            'control1EffortRear' => null,
            'control1EffortSidecar' => null,
            'control2EffortFront' => null,
            'control2EffortRear' => null,
            'control2EffortSidecar' => null,
            'brakeTestType' => 'DECEL',
            'vehicleWeightFront' => null,
            'vehicleWeightRear' => null,
            'riderWeight' => null,
            'isSidecarAttached' => 0,
            'sidecarWeight' => null,
            'control1BrakeEfficiency' => $dataMap['control1BrakeEfficiency'],
            'control2BrakeEfficiency' => $dataMap['control2BrakeEfficiency'],
        ]);

        return $this->client->request(new Request(
            'POST',
            str_replace('{mot_test_number}', $motNumber, self::PATH),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }

    public function addBrakeTestGradientClass1To2WithCustomData($token, $motNumber, $param)
    {

        $body = json_encode([
            'gradientControl1AboveUpperMinimum' => $param['control1Above30'],
            'gradientControl2AboveUpperMinimum' => $param['control2Above30'],
            'gradientControl1BelowMinimum'      => $param['control1Below25'],
            'gradientControl2BelowMinimum'      => $param['control2Below25'],
            'brakeTestType' => 'GRADT'
        ]);

        return $this->client->request(new Request(
            'POST',
            str_replace('{mot_test_number}', $motNumber, self::PATH),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token],
            $body
        ));
    }

    public function addBrakeTestFloorClass1To2WithCustomData($token, $motNumber, $param)
    {
        $body = json_encode([
            'control1EffortFront' => $param['control1Effort'],
            'control2EffortFront' => $param['control2Effort'],
            'vehicleWeightFront' => $param['vehicleWeightFront'],
            'vehicleWeightRear' => $param['vehicleWeightRear'],
            'riderWeight' => $param['riderWeight'],
            'isSidecarAttached' => 0,
            'sidecarWeight' => null,
            'brakeTestType' => 'FLOOR'
        ]);

        return $this->client->request(new Request(
            'POST',
            str_replace('{mot_test_number}', $motNumber, self::PATH),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }
}
