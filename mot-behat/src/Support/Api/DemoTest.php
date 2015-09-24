<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\Request;

class DemoTest extends AbstractMotTest
{
    const PATH = 'mot-demo-test';

    public function getPath()
    {
        return self::PATH;
    }

    public function startMotTest($token = null, $vehicleId = '3', $testClass = '4')
    {
        $params = [
            'vehicleId' => $vehicleId,
            'vehicleTestingStationId' => '1',
            'primaryColour' => 'L',
            'secondaryColour' => 'L',
            'fuelTypeId' => 'PE',
            'vehicleClassCode' => $testClass,
            'hasRegistration' => '1',
            'oneTimePassword' => '',
        ];

        return parent::createMotWithParams($token, $params);
    }

}
