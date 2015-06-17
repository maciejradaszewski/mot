<?php

namespace OrganisationTest\Presenter;

use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use Organisation\Presenter;
use DvsaCommon\Dto\Organisation;
use Organisation\Presenter\AuthorisedExaminerPresenter;

/**
 * Class PresenterTest
 * @package OrganisationTest\Presenter
 */
class AuthorisedExaminerPresenterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName() {
        $name = "test";

        $organisationDto = $this->getOrganisationDto();
        $organisationDto->setName($name);
        $presenter = new AuthorisedExaminerPresenter($organisationDto);

        $this->assertEquals($name, $presenter->getName());
    }

    public function testGetNumber() {
        $companyNumber = "123456789";

        $organisationDto = $this->getOrganisationDto();
        $organisationDto->setRegisteredCompanyNumber($companyNumber);
        $presenter = new AuthorisedExaminerPresenter($organisationDto);

        $this->assertEquals($companyNumber, $presenter->getNumber());
    }

    public function testGetTradingName() {
        $trading = "test";

        $organisationDto = $this->getOrganisationDto();
        $organisationDto->setTradingAs($trading);
        $presenter = new AuthorisedExaminerPresenter($organisationDto);

        $this->assertEquals($trading, $presenter->getTradingName());
    }

    public function testGetAddressInline() {
        $addressDto = $this->getAddressDto();

        $organisationContactDto = new OrganisationContactDto();
        $organisationContactDto->setType(OrganisationContactTypeCode::REGISTERED_COMPANY);
        $organisationContactDto->setAddress($addressDto);

        $organisationDto = $this->getOrganisationDto();
        $organisationDto->setContacts(array($organisationContactDto));
        $presenter = new AuthorisedExaminerPresenter($organisationDto);

        $this->assertEquals("Line1, Line2, Line3, Line4", $presenter->getAddressInline());
    }

    public function testGetOrganisationType() {
        $organisationType = 1;

        $organisationDto = $this->getOrganisationDto();
        $organisationDto->setOrganisationType($organisationType);
        $presenter = new AuthorisedExaminerPresenter($organisationDto);

        $this->assertEquals($organisationType, $presenter->getOrganisationType());
    }

    private function getOrganisationDto() {
        $authorisation = new Organisation\AuthorisedExaminerAuthorisationDto();
        $authorisation->setAuthorisedExaminerRef('123456789');

        $dto = new OrganisationDto();
        $dto->setAuthorisedExaminerAuthorisation($authorisation);

        return $dto;
    }

    public function testGetSlotUsage() {
        $organisationDto = new OrganisationDto();
        $organisationDto->setSlotBalance('1200');

        $presenter = new AuthorisedExaminerPresenter($organisationDto);

        $this->assertEquals($presenter->getSlotsBalance(), '1200');
    }

    private function getAddressDto() {
        $addressDto = new AddressDto();
        $addressDto->setAddressLine1("Line1");
        $addressDto->setAddressLine2("Line2");
        $addressDto->setAddressLine3("Line3");
        $addressDto->setAddressLine4("Line4");

        return $addressDto;
    }
}