<?php

namespace Dvsa\Mot\Behat\Support\Api;

use DvsaCommon\Date\DateUtils;
use Dvsa\Mot\Behat\Datasource\Authentication;
use Dvsa\Mot\Behat\Support\HttpClient;
use Dvsa\Mot\Behat\Support\Request;

class ContingencyTest extends MotApi
{
    const PATH = 'emergency-log';
    const PATH_START_CONT_TEST = 'mot-test';

    /**
     * @param $token
     * @param string $contingencyCode
     * @param string $reasonCode
     *
     * @return array
     */
    public function getContingencyCodeID($token, $contingencyCode, $reasonCode)
    {
        //use generic password if $password is "default"
        $contingencyCode = strcasecmp($contingencyCode, 'DEFAULT') == 0 ? Authentication::CONTINGENCY_CODE_DEFAULT : $contingencyCode;

        $testedByWhom = 'current';
        $testerCode = 'current';
        $siteId = '1';
        $reasonText = 'some reason text';

        $today =  DateUtils::today();

        $body = json_encode([
            'testedByWhom' => $testedByWhom,
            'testerCode' => $testerCode,
            'reasonText' => $reasonText,
            'siteId' => $siteId,
            'contingencyCode' => $contingencyCode,
            'performedAt' => $today->format('Y-m-d'),
            'dateYear' => $today->format('Y'),
            'dateMonth' => $today->format('m'),
            'dateDay' => $today->format('d'),
            'reasonCode' => $reasonCode,
            '_class' => '\\DvsaCommon\\Dto\MotTesting\\ContingencyMotTestDto',
        ]);

        return $this->client->request(new Request(
            'POST',
            self::PATH,
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }

    public function startContingencyTest($token, $contingencyId, $vehicleId, $vehicleClass)
    {
        $today =  DateUtils::today();

        $body = json_encode([
            'vehicleId' => $vehicleId,
            'vehicleTestingStationId' => 1,
            'primaryColour' => 'C',
            'secondaryColour' => 'C',
            'fuelTypeId' => 'PE',
            'vehicleClassCode' => $vehicleClass,
            'hasRegistration' => true,
            'oneTimePassword' => Authentication::ONE_TIME_PASSWORD,
            'contingencyId' => $contingencyId,
            'contingencyDto' => [
                    'testerCode' => '',
                    'contingencyCode' => Authentication::CONTINGENCY_CODE_DEFAULT,
                    'performedAt' => $today->format('Y-m-d'),
                    'reasonCode' => 'SO',
                    'reasonText' => '',
                    'siteId' => '1',
                    'testType' => 'normal',
                    'testedByWhom' => 'current',
                    'dateYear' => $today->format('Y'),
                    'dateMonth' => $today->format('m'),
                    'dateDay' => $today->format('d'),
                    '_class' => 'DvsaCommon\\Dto\\MotTesting\\ContingencyMotTestDto',
                ],
        ]);

        return $this->client->request(new Request(
            'POST',
            self::PATH_START_CONT_TEST,
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }
}
