<?php

require_once 'configure_autoload.php';

use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Contact\EmailDto;
use DvsaCommon\Dto\Contact\PhoneDto;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Dto\Site\SiteDto;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommon\Utility\DtoHydrator;
use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\VehicleTestingStationUrlBuilder;

class VM7285SetContactToSite
{
    private $result;
    /** @var  SiteContactDto */
    private $contactDto;

    private $userName;
    private $id;

    public function execute()
    {
        $urlBuilder = VehicleTestingStationUrlBuilder::contactUpdate($this->id);

        $this->result = TestShared::execCurlFormPutForJsonFromUrlBuilder(
            (new CredentialsProvider($this->userName, TestShared::PASSWORD)),
            $urlBuilder,
            DtoHydrator::dtoToJson($this->contactDto)
        );
    }

    public function reset()
    {
        $this->contactDto = (new SiteContactDto())
            ->setAddress(new AddressDto());

        $this->result = null;
    }

    public function isSaved()
    {
        $urlBuilder = VehicleTestingStationUrlBuilder::vtsById($this->id)->queryParam('dto', true);

        $this->result = TestShared::execCurlForJsonFromUrlBuilder(
            (new CredentialsProvider($this->userName, TestShared::PASSWORD)),
            $urlBuilder
        );

        /** @var SiteDto $dto */
        $dto = DtoHydrator::jsonToDto($this->result['data']);

        $isEquals = SiteContactDto::isEquals(
            $dto->getContactByType($this->contactDto->getType()),
            $this->contactDto
        );

        return ($isEquals ? 'OK' : 'FAILED');
    }

    public function errorMessage()
    {
        if (TestShared::resultIsSuccess($this->result)) {
            return 'OK';
        }

        return TestShared::errorMessages($this->result);
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function setSiteMngUserName($value)
    {
        $this->userName = $value;
    }

    public function setType($value)
    {
        $this->contactDto->setType($value);
    }

    public function setAddressLine1($value)
    {
        $this->contactDto->getAddress()->setAddressLine1($value);
    }

    public function setAddressLine2($value)
    {
        $this->contactDto->getAddress()->setAddressLine2($value);
    }

    public function setAddressLine3($value)
    {
        $this->contactDto->getAddress()->setAddressLine3($value);
    }

    public function setTown($value)
    {
        $this->contactDto->getAddress()->setTown($value);
    }

    public function setPostcode($value)
    {
        $this->contactDto->getAddress()->setPostcode($value);
    }

    public function setPhoneNumber($value)
    {
        $this->contactDto->setPhones(
            [
                (new PhoneDto)
                    ->setNumber($value)
                    ->setContactType(PhoneContactTypeCode::BUSINESS)
                    ->setIsPrimary(true)
            ]
        );
    }

    public function setEmail($value)
    {
        $this->contactDto->setEmails(
            [
                (new EmailDto)
                    ->setEmail($value)
                    ->setIsPrimary(true)
            ]
        );
    }
}
