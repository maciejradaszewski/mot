<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;
use DvsaCommon\Dto\Site\FacilityDto;
use DvsaCommon\Dto\Site\FacilityTypeDto;
use DvsaCommon\Constants\FacilityTypeCode;
/**
 * Checks response after posting to api to create a new site
 */
class CreateSite
{
    private $result;
    private $input = [];
    public $username = 'areaoffice1user';
    public $password = TestShared::PASSWORD;

    public function execute()
    {
        $urlBuilder = (new UrlBuilder())->vehicleTestingStation();
        $dto = $this->generateSiteDto();

        $this->result = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            $this,
            $urlBuilder,
            \DvsaCommon\Utility\DtoHydrator::dtoToJson($dto)
        );
    }

    private function generateSiteDto()
    {
        $address = (new \DvsaCommon\Dto\Contact\AddressDto())
            ->setAddressLine1($this->input['addressLine1'])
            ->setAddressLine2($this->input['addressLine2'])
            ->setAddressLine3($this->input['addressLine3'])
            ->setPostcode($this->input['postcode'])
            ->setTown($this->input['town']);

        $email = (new \DvsaCommon\Dto\Contact\EmailDto())
            ->setEmail($this->input['email'])
            ->setIsPrimary(true);

        $phone = (new \DvsaCommon\Dto\Contact\PhoneDto())
            ->setNumber($this->input['phoneNumber'])
            ->setContactType(\DvsaCommon\Enum\PhoneContactTypeCode::BUSINESS)
            ->setIsPrimary(true);

        $contact = new \DvsaCommon\Dto\Site\SiteContactDto();
        $contact
            ->setType(\DvsaCommon\Enum\SiteContactTypeCode::BUSINESS)
            ->setAddress($address)
            ->setEmails([$email])
            ->setPhones([$phone]);

        $facility = (new FacilityDto())
            ->setName('OPTL')
            ->setType((new FacilityTypeDto())->setCode(FacilityTypeCode::ONE_PERSON_TEST_LANE));

        //  logical block :: assemble dto
        $siteDto = new \DvsaCommon\Dto\Site\VehicleTestingStationDto();
        $siteDto
            ->setName($this->input['name'])
            ->setType(\DvsaCommon\Enum\SiteTypeCode::VEHICLE_TESTING_STATION)
            ->setTestClasses($this->input['classes'])
            ->setIsDualLanguage(false)
            ->setFacilities([$facility])
            ->setIsOptlSelected(true)
            ->setIsTptlSelected(true)
            ->setContacts([$contact]);

        return $siteDto;
    }

    public function reset()
    {
        $this->input = [];
        $this->result = null;
    }

    public function result()
    {
        return $this->result['data'];
    }

    private function setInputValue($name, $value)
    {
        if (!empty($value)) {
            $this->input[$name] = $value;
        }
    }

    public function setName($value)
    {
        $this->setInputValue('name', $value);
    }

    public function setAddressLine1($value)
    {
        $this->setInputValue('addressLine1', $value);
    }

    public function setAddressLine2($value)
    {
        $this->setInputValue('addressLine2', $value);
    }

    public function setAddressLine3($value)
    {
        $this->setInputValue('addressLine3', $value);
    }

    public function setTown($value)
    {
        $this->setInputValue('town', $value);
    }

    public function setPostcode($value)
    {
        $this->setInputValue('postcode', $value);
    }

    public function setPhoneNumber($value)
    {
        $this->setInputValue('phoneNumber', $value);
    }

    public function setEmail($value)
    {
        $this->setInputValue('email', $value);
    }

    public function setClasses($value)
    {
        $this->setInputValue('classes', explode(',', $value));
    }

    public function errorMessage()
    {
        var_dump($this->result());
        return TestShared::errorMessages($this->result);
    }
}
