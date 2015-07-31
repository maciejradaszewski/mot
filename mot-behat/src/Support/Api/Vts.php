<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\Request;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Contact\EmailDto;
use DvsaCommon\Dto\Contact\PhoneDto;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommon\Enum\SiteTypeCode;
use DvsaCommon\UrlBuilder\UrlBuilder;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommon\Dto\Site\FacilityDto;
use DvsaCommon\Dto\Site\FacilityTypeDto;
use DvsaCommon\Constants\FacilityTypeCode;

class Vts extends MotApi
{
    const SITE_NAME = 'Garage Name';
    const PATH = 'vehicle-testing-station/{vts_id}';
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
            'postcode' => 'BT2 4RR',
            'email' => 'dummy@dummy.com',
            'phoneNumber' => '01117 26374',
            'classes' => [1, 2, 3, 4, 5, 7],
        ];
        $site = array_merge($default, $site);

        $body = json_encode(DtoHydrator::dtoToJson($this->generateSiteDto($site)));

        return $this->client->request(
            new Request(
                'POST',
                UrlBuilder::of()->vehicleTestingStation()->toString(),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
                $body
            )
        );
    }

    private function generateSiteDto($site)
    {
        $address = (new AddressDto())
            ->setAddressLine1($site['addressLine1'])
            ->setPostcode($site['postcode'])
            ->setTown($site['town']);

        $email = (new EmailDto())
            ->setEmail($site['email'])
            ->setIsPrimary(true);

        $phone = (new PhoneDto())
            ->setNumber($site['phoneNumber'])
            ->setContactType(PhoneContactTypeCode::BUSINESS)
            ->setIsPrimary(true);

        $contact = new SiteContactDto();
        $contact
            ->setType(SiteContactTypeCode::BUSINESS)
            ->setAddress($address)
            ->setEmails([$email])
            ->setPhones([$phone]);

        $facility = (new FacilityDto())
            ->setName('OPTL')
            ->setType((new FacilityTypeDto())->setCode(FacilityTypeCode::ONE_PERSON_TEST_LANE));

        //  logical block :: assemble dto
        $siteDto = new VehicleTestingStationDto();
        $siteDto
            ->setName($site['name'])
            ->setType(SiteTypeCode::VEHICLE_TESTING_STATION)
            ->setTestClasses($site['classes'])
            ->setIsDualLanguage(false)
            ->setFacilities([$facility])
            ->setIsOptlSelected(true)
            ->setIsTptlSelected(true)
            ->setContacts([$contact]);

        return $siteDto;
    }
}
