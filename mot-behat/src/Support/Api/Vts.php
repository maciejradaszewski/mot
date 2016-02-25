<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\Request;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Contact\EmailDto;
use DvsaCommon\Dto\Contact\PhoneDto;
use DvsaCommon\Dto\Site\EnforcementSiteAssessmentDto;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommon\Enum\SiteTypeCode;
use DvsaCommon\Model\VehicleTestingStation;
use DvsaCommon\UrlBuilder\UrlBuilder;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilder;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommon\Dto\Site\FacilityDto;
use DvsaCommon\Dto\Site\FacilityTypeDto;
use DvsaCommon\Constants\FacilityTypeCode;
use DvsaCommon\Validator\EmailAddressValidator;

class Vts extends MotApi
{
    const SITE_NAME = 'Garage Name';
    const PATH = 'vehicle-testing-station/{vts_id}';
    const SEARCH = 'vehicle-testing-station/search';
    const POSITION = 'site/{site_id}/position';
    const TESTING_FACILITIES = 'vehicle-testing-station/{site_id}/testing-facilities';
    const SITE_DETAILS = 'vehicle-testing-station/{site_id}/site-details';
    const RISK_ASSESMENT = 'vehicle-testing-station/{site_id}/risk-assessment';

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
            'email'        => 'vtsbehatsupport@' . EmailAddressValidator::TEST_DOMAIN,
            'phoneNumber'  => '01117 26374',
            'classes'      => [1, 2, 3, 4, 5, 7],
        ];
    }

    public function create($token, $site = [])
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
        $body = json_encode($site);

        return $this->client->request(
            new Request(
                'PATCH',
                VehicleTestingStationUrlBuilder::vtsDetails($siteId),
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

    public function addRiskAssessment($token, $siteId, $riskAssessment = [])
    {
        $defaults = [
            "id" => null,
            "siteAssessmentScore" => null,
            "aeRepresentativesFullName" => null,
            "aeRepresentativesRole" => null,
            "aeRepresentativesUserId" => null,
            "testerUserId" => null,
            "testerFullName" => null,
            "dvsaExaminersFullName" => null,
            "dvsaExaminersUserId" => null,
            "dateOfAssessment" => null,
            "siteId" => $siteId,
            "aeOrganisationId" => null,
            "validateOnly" => false,
            "userIsNotAssessor" => false,
            "_class" => "DvsaCommon\\Dto\\Site\\EnforcementSiteAssessmentDto"
        ];

        $riskAssessment = array_replace($defaults, $riskAssessment);

        $body = json_encode($riskAssessment);

        return $this->client->request(
            new Request(
                'POST',
                str_replace('{site_id}', $siteId, self::RISK_ASSESMENT),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
                $body
            )
        );
    }

    public function getRiskAssessment($token, $siteId)
    {
        return $this->client->request(
            new Request(
                'GET',
                str_replace('{site_id}', $siteId, self::RISK_ASSESMENT),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token]
            )
        );
    }

    public function removeAllTestClasses($token, $siteId)
    {
        return $this->updateSiteDetails($token, $siteId,
            [
                VehicleTestingStation::PATCH_PROPERTY_CLASSES => [],
                '_class' => VehicleTestingStationDto::class,
            ]);
    }
}