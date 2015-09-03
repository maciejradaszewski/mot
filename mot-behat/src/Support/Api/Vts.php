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
    const TESTING_FACILITIES = 'vehicle-testing-station/{site_id}/testing-facilities';
    const SITE_DETAILS = 'vehicle-testing-station/{site_id}/site-details';

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

    protected function getDefaults()
    {
        return [
            'name'         => 'Garage Name',
            'status'       => 'AV',
            'addressLine1' => 'addressLine1',
            'town'         => 'Boston',
            'postcode'     => 'BT2 4RR',
            'email'        => 'dummy@dummy.com',
            'phoneNumber'  => '01117 26374',
            'classes'      => [1, 2, 3, 4, 5, 7],
        ];
    }

    public function create($token, $site)
    {
        $site = array_merge($this->getDefaults(), $site);

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

    /**
     * @param string $token
     * @param int $siteId
     * @param array $site
     * @param int $numOptl
     * @param int $numTptl
     */
    public function updateTestingFacilities($token, $siteId, array $site, $numOptl, $numTptl)
    {
        $site = array_merge($this->getDefaults(), $site);
        $siteDto = $this->generateSiteDto($site);

        // add number of specified testing facilities
        $facilities = [];
        for ($i = 0; $i < $numOptl; $i++) {
            $facility = (new FacilityDto())
                ->setName('OPTL')
                ->setType((new FacilityTypeDto())->setCode(FacilityTypeCode::ONE_PERSON_TEST_LANE));
            array_push($facilities, $facility);
        }

        for ($i = 0; $i < $numTptl; $i++) {
            $facility = (new FacilityDto())
                ->setName('TPTL')
                ->setType((new FacilityTypeDto())->setCode(FacilityTypeCode::TWO_PERSON_TEST_LANE));
            array_push($facilities, $facility);
        }

        $siteDto->setFacilities($facilities);
        $body = json_encode(DtoHydrator::dtoToJson($siteDto));

        return $this->client->request(
            new Request(
                'PUT',
                str_replace('{site_id}', $siteId, self::TESTING_FACILITIES),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
                $body
            )
        );
    }

    public function updateSiteDetails($token, $siteId, array $site)
    {
        $site = array_merge($this->getDefaults(), $site);
        $siteDto = $this->generateSiteDto($site);
        $body = json_encode(DtoHydrator::dtoToJson($siteDto));

        return $this->client->request(
            new Request(
                'PUT',
                str_replace('{site_id}', $siteId, self::SITE_DETAILS),
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
            ->setStatus($site['status'])
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