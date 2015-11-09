<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Datasource\Authentication;
use Dvsa\Mot\Behat\Support\Request;
use DvsaCommon\Date\DateUtils;

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
        $siteId = '1';
        $otherReasonText = 'some reason text';
        $today =  DateUtils::today();

        $body = json_encode([
            'siteId'            => $siteId,
            'contingencyCode'   => $contingencyCode,
            'performedAtYear'   => $today->format('Y'),
            'performedAtMonth'  => $today->format('m'),
            'performedAtDay'    => $today->format('d'),
            'performedAtHour'   => $today->format('g'),
            'performedAtMinute' => $today->format('i'),
            'performedAtAmPm'   => $today->format('a'),
            'reasonCode'        => $reasonCode,
            'otherReasonText'   => $otherReasonText,
            '_class'            => '\\DvsaCommon\\Dto\MotTesting\\ContingencyTestDto',
        ]);

        return $this->client->request(new Request(
            'POST',
            self::PATH,
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token],
            $body
        ));
    }

    public function startContingencyTest($token, $contingencyId, $vehicleId, $vehicleClass)
    {
        $today =  DateUtils::today();

        $body = json_encode([
            'vehicleId'               => $vehicleId,
            'vehicleTestingStationId' => 1,
            'primaryColour'           => 'C',
            'secondaryColour'         => 'C',
            'fuelTypeId'              => 'PE',
            'vehicleClassCode'        => $vehicleClass,
            'hasRegistration'         => true,
            'oneTimePassword'         => Authentication::ONE_TIME_PASSWORD,
            'contingencyId'           => $contingencyId,
            'contingencyDto'          => [
                    'contingencyCode'   => Authentication::CONTINGENCY_CODE_DEFAULT,
                    'reasonCode'        => 'SO',
                    'otherReasonText'   => '',
                    'siteId'            => '1',
                    'testType'          => 'normal',
                    'performedAtYear'   => $today->format('Y'),
                    'performedAtMonth'  => $today->format('m'),
                    'performedAtDay'    => $today->format('d'),
                    'performedAtHour'   => $today->format('g'),
                    'performedAtMinute' => $today->format('i'),
                    'performedAtAmPm'   => $today->format('a'),
                    '_class'            => 'DvsaCommon\\Dto\\MotTesting\\ContingencyTestDto',
                ],
        ]);

        return $this->client->request(new Request(
            'POST',
            self::PATH_START_CONT_TEST,
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token],
            $body
        ));
    }
}
