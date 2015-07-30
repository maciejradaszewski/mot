<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\Request;
use DvsaCommon\UrlBuilder\UrlBuilder;

class Vts extends MotApi
{
    const PATH = 'vehicle-testing-station/site/{vts_id}';
    const SEARCH = 'vehicle-testing-station/search';
    const POSITION = 'site/{site_id}/position';

    public function getVtsDetails($vtsId, $token)
    {
        return $this->client->request(
            new Request(
                'GET',
                str_replace('{vts_id}', $vtsId, self::PATH),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token]
            )
        );
    }

    public function searchVts($params, $token)
    {
        return $this->sendRequest(
            $token,
            MotApi::METHOD_POST,
            self::SEARCH,
            $params
        );
    }

    public function nominateToRole($nomineeId, $siteRoleCode, $siteId, $token)
    {
        $data = [
            "nomineeId" => $nomineeId,
            "roleCode" => $siteRoleCode
        ];

        return $this->sendRequest(
            $token,
            MotApi::METHOD_POST,
            str_replace("{site_id}", $siteId, self::POSITION),
            $data
        );
    }

    public function create($token, $site)
    {
        $default = [
            'name' => 'Garage Name',
            'addressLine1' => 'addressLine1',
            'town' => 'Boston',
            'country' => 'England',
            'postcode' => 'BT2 4RR',
            'email' => 'dummy@dummy.com',
            'phoneNumber' => '01117 26374',
            'correspondenceName' => 'Garage Name',
            'correspondenceAddressLine1' => 'addressLine1',
            'correspondenceTown' => 'Bristol',
            'correspondencePostcode' => 'BS7 8RR',
            'correspondenceEmail' => 'dummy@dummy.com',
            'correspondencePhoneNumber' => '01117 26374',
            'nonWorkingDayCountry' => 'GBENG',
        ];
        $site = array_merge($default, $site);

        $body = json_encode($site);

        return $this->client->request(
            new Request(
                'POST',
                UrlBuilder::of()->vehicleTestingStation()->toString(),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
                $body
            )
        );
    }
}
