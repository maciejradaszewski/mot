<?php

namespace SiteTest\Form;

use DvsaClient\Entity\ContactDetail;
use DvsaClient\Entity\Address;
use DvsaClient\Entity\Email;
use DvsaClient\Entity\Phone;
use DvsaCommon\Auth\Assertion\UpdateVtsAssertion;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\XMock;
use Site\Form\VehicleTestingStationForm;
use \PHPUnit_Framework_MockObject_MockObject as MockObject;

class VehicleTestingStationFormTest extends \PHPUnit_Framework_TestCase
{
    const VTS_ID = 1;

    /** @var VehicleTestingStationForm */
    private $form;

    public function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);

        /** @var UpdateVtsAssertion|MockObject $assertion */
        $assertion = XMock::of(UpdateVtsAssertion::class);
        $serviceManager->setService(UpdateVtsAssertion::class, $assertion);

        $this->form = new VehicleTestingStationForm();

        $this->form->setUpdateVtsAssertion($assertion);
        $this->form->setVtsId(self::VTS_ID);

        $assertion->expects($this->any())
            ->method('canUpdateName')
            ->willReturn(true);
        $assertion->expects($this->any())
            ->method('canUpdateBusinessDetails')
            ->willReturn(true);
        $assertion->expects($this->any())
            ->method('canUpdateCorrespondenceDetails')
            ->willReturn(true);
    }

    public function testGetterSetter()
    {
        $this->assertTrue($this->form->canEditVtsName());
        $this->assertTrue($this->form->canEditBusinessContact());
        $this->assertTrue($this->form->canEditCorrespondenceContact());

        $input = $this->getInput() + [
            'correspondenceContactSame' => false,
        ];
        $this->form->populateFromInput($input);
        $this->assertEquals($this->form->toArray(), $this->getInput());
        $this->assertTrue($this->form->isValid());
        $this->assertEquals('name', $this->form->getName());
        $this->assertEquals(false, $this->form->getCorrespondenceContactSame());
        $this->assertEquals('addressLine1', $this->form->getAddressLine1());
        $this->assertEquals('addressLine2', $this->form->getAddressLine2());
        $this->assertEquals('addressLine3', $this->form->getAddressLine3());
        $this->assertEquals('correspondenceAddressLine1', $this->form->getCorrespondenceAddressLine1());
        $this->assertEquals('correspondenceAddressLine2', $this->form->getCorrespondenceAddressLine2());
        $this->assertEquals('correspondenceAddressLine3', $this->form->getCorrespondenceAddressLine3());
        $this->assertEquals('correspondenceEmail', $this->form->getCorrespondenceEmail());
        $this->assertEquals('correspondenceFaxNumber', $this->form->getCorrespondenceFaxNumber());
        $this->assertEquals('correspondencePhoneNumber', $this->form->getCorrespondencePhoneNumber());
        $this->assertEquals('correspondencePostcode', $this->form->getCorrespondencePostcode());
        $this->assertEquals('correspondenceTown', $this->form->getCorrespondenceTown());
        $this->assertEquals('email', $this->form->getEmail());
        $this->assertEquals('faxNumber', $this->form->getFaxNumber());
        $this->assertEquals('phoneNumber', $this->form->getPhoneNumber());
        $this->assertEquals('postcode', $this->form->getPostcode());
        $this->assertEquals('town', $this->form->getTown());
    }

    public function testPopulateFromApi()
    {
        $apiResult = $this->getApiResults();
        $this->form->populateFromApi($apiResult);

        $this->assertEquals('name', $this->form->getName());
        $this->assertEquals(true, $this->form->getCorrespondenceContactSame());
        $this->assertEquals('', $this->form->getAddressLine1());
        $this->assertEquals('', $this->form->getAddressLine2());
        $this->assertEquals('', $this->form->getAddressLine3());
        $this->assertEquals('', $this->form->getCorrespondenceAddressLine1());
        $this->assertEquals('', $this->form->getCorrespondenceAddressLine2());
        $this->assertEquals('', $this->form->getCorrespondenceAddressLine3());
        $this->assertEquals('', $this->form->getCorrespondenceEmail());
        $this->assertEquals('', $this->form->getCorrespondenceFaxNumber());
        $this->assertEquals('', $this->form->getCorrespondencePhoneNumber());
        $this->assertEquals('', $this->form->getCorrespondencePostcode());
        $this->assertEquals('', $this->form->getCorrespondenceTown());
        $this->assertEquals('', $this->form->getEmail());
        $this->assertEquals('', $this->form->getFaxNumber());
        $this->assertEquals('', $this->form->getPhoneNumber());
        $this->assertEquals('', $this->form->getPostcode());
        $this->assertEquals('', $this->form->getTown());
    }

    private function getApiResults()
    {
        $address = (new Address());
        $email = (new Email());
        $phone = (new Phone());
        $businessContact = (new ContactDetail())
            ->setAddress($address)
            ->setEmails([$email])
            ->setFaxNumber('')
            ->setPhones([$phone])
            ->setType(SiteContactTypeCode::BUSINESS);

        $correspondenceContact = (new ContactDetail())
            ->setAddress($address)
            ->setEmails([$email])
            ->setFaxNumber('')
            ->setPhones([$phone])
            ->setType(SiteContactTypeCode::CORRESPONDENCE);

        return [
            'name' => 'name',
            'contacts' => [
                $businessContact,
                $correspondenceContact,
            ]
        ];
    }

    private function getInput()
    {
        return [
            'name' => 'name',
            'addressLine1' => 'addressLine1',
            'addressLine2' => 'addressLine2',
            'addressLine3' => 'addressLine3',
            'town' => 'town',
            'postcode' => 'postcode',
            'email' => 'email',
            'emailConfirmation' => 'emailConfirmation',
            'phoneNumber' => 'phoneNumber',
            'faxNumber' => 'faxNumber',
            'correspondenceAddressLine1' => 'correspondenceAddressLine1',
            'correspondenceAddressLine2' => 'correspondenceAddressLine2',
            'correspondenceAddressLine3' => 'correspondenceAddressLine3',
            'correspondenceTown' => 'correspondenceTown',
            'correspondencePostcode' => 'correspondencePostcode',
            'correspondenceEmail' => 'correspondenceEmail',
            'correspondenceEmailConfirmation' => 'correspondenceEmailConfirmation',
            'correspondencePhoneNumber' => 'correspondencePhoneNumber',
            'correspondenceFaxNumber' => 'correspondenceFaxNumber',
        ];
    }
}