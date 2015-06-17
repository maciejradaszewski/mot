<?php

use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Contact\EmailDto;
use DvsaCommon\Dto\Contact\PhoneDto;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommon\Utility\DtoHydrator;
use MotFitnesse\Util\AuthorisedExaminerUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Updating Authorised Examiner business and contact details
 */
class UpdateAuthorisedExaminer
{
    private $result;
    /** @var  OrganisationDto */
    private $orgDto;
    /** @var  OrganisationContactDto */
    private $busContactDto;
    /** @var  OrganisationContactDto */
    private $corrContactDto;

    private $id;
    private $username;
    private $isTheSameAddress;

    public function execute()
    {
        $urlBuilder = AuthorisedExaminerUrlBuilder::authorisedExaminer()->routeParam('id', $this->id);

        $this->result = TestShared::execCurlFormPutForJsonFromUrlBuilder(
            (new \MotFitnesse\Util\CredentialsProvider($this->username, TestShared::PASSWORD)),
            $urlBuilder,
            $this->prepareData()
        );
    }

    public function reset()
    {
        $this->busContactDto = (new OrganisationContactDto())
            ->setAddress(new AddressDto())
            ->setType(OrganisationContactTypeCode::REGISTERED_COMPANY);

        $this->corrContactDto = (new OrganisationContactDto())
            ->setAddress(new AddressDto())
            ->setType(OrganisationContactTypeCode::CORRESPONDENCE);

        $this->orgDto = new OrganisationDto();
        $this->orgDto->setContacts([$this->busContactDto, $this->corrContactDto]);

        $this->result = null;
    }

    public function canUpdate()
    {
        if (TestShared::resultIsSuccess($this->result)) {
            return 'true';
        }

        return TestShared::errorMessages($this->result);
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    private function prepareData()
    {
        $this->orgDto->setId($this->id);
        $this->orgDto->setName("orgName");
        $this->orgDto->setTradingAs("tradingAS");
        $this->orgDto->setOrganisationType("Examining body");
        $this->orgDto->setRegisteredCompanyNumber("12345678");

        $this->busContactDto->getAddress()->setAddressLine1("addressLine1");
        $this->busContactDto->getAddress()->setAddressLine2("addressLine2");
        $this->busContactDto->getAddress()->setAddressLine3("addressLine3");
        $this->busContactDto->getAddress()->setTown("town");
        $this->busContactDto->getAddress()->setPostcode("33-234");
        $this->setPhoneNumber("098634975");
        $this->setEmail("bus@email.com");

        if (!$this->isTheSameAddress) {
            $this->corrContactDto->getAddress()->setAddressLine1("corr addressLine1");
            $this->corrContactDto->getAddress()->setAddressLine2("corr addressLine2");
            $this->corrContactDto->getAddress()->setAddressLine3("corr addressLine3");
            $this->corrContactDto->getAddress()->setTown("corr town");
            $this->corrContactDto->getAddress()->setPostcode("44-888");
            $this->setCorrespondencePhoneNumber("044488777");
            $this->setCorrespondenceEmail("corr@email.com");
        }

        $this->orgDto->setContacts([$this->busContactDto, $this->corrContactDto]);

        return DtoHydrator::dtoToJson($this->orgDto);
    }

    private function setPhoneNumber($value)
    {
        $this->busContactDto->setPhones(
            [
                (new PhoneDto)
                    ->setNumber($value)
                    ->setContactType(PhoneContactTypeCode::BUSINESS)
                    ->setIsPrimary(true)
            ]
        );
    }

    private function setEmail($value)
    {
        $this->busContactDto->setEmails(
            [
                (new EmailDto)
                    ->setEmail($value)
                    ->setIsPrimary(true)
            ]
        );
    }

    public function setCorrespondenceAddressSameYes($value)
    {
        if ('Yes' === $value) {
            $this->isTheSameAddress = true;
        } else {
            $this->isTheSameAddress = false;
        }
    }

    public function setCorrespondenceEmail($value)
    {
        $this->corrContactDto->setEmails(
            [
                (new EmailDto)
                    ->setEmail($value)
                    ->setIsPrimary(true)
            ]
        );
    }

    public function setCorrespondencePhoneNumber($value)
    {
        $this->corrContactDto->setPhones(
            [
                (new PhoneDto)
                    ->setNumber($value)
                    ->setContactType(PhoneContactTypeCode::BUSINESS)
                    ->setIsPrimary(true)
            ]
        );
    }
}
