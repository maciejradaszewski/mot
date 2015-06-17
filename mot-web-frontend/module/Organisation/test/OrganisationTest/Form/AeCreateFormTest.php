<?php

namespace OrganisationTest\Form;

use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Contact\EmailDto;
use DvsaCommon\Dto\Contact\PhoneDto;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Enum\PhoneContactTypeCode;
use Organisation\Form\AeCreateForm;

/**
 * I'm building my professional career on comments
 */
class AeCreateFormTest extends \PHPUnit_Framework_TestCase
{
    private $input = [
        'organisationName' => 'Adria Velasquez',
        'tradingAs' => 'Tempora neque distinctio Ipsum amet aut et beat',
        'organisationType' => 'Registered Company',
        'companyType' => null,
        'registeredCompanyNumber' => 'Hooper Hayden Co',
        'addressLine1' => 'Dolor magna neque proident accusantium reici',
        'addressLine2' => 'Sed elit maxime quas id aspernatur anim dig',
        'addressLine3' => 'Consequatur Dolore et sit animi quia ut qu',
        'town' => 'Qui doloribus odio est aut amet est ipsam invento',
        'postcode' => 'Omnis face',
        'email' => 'xewyd@hotmail.com',
        'emailConfirmation' => 'xewyd@hotmail.com',
        'phoneNumber' => '+755-53-6994780',
        'faxNumber' => '+225-92-7920260',
        'correspondenceContactDetailsSame' => null,
        'correspondenceAddressLine1' => 'Quis nostrud nulla laboris facilis quam aute ',
        'correspondenceAddressLine2' => 'Mollitia error adipisci sed aliquid proident',
        'correspondenceAddressLine3' => 'Nemo suscipit ut deserunt consectetur quo po',
        'correspondenceTown' => 'Illum exercitation totam proident dolor ea quod ',
        'correspondencePostcode' => 'Dolore nos',
        'correspondenceEmail' => 'vujuw@gmail.com',
        'correspondenceEmailConfirmation' => 'vujuw@gmail.com',
        'correspondencePhoneNumber' => '+696-40-7816619',
        'correspondenceFaxNumber' => '+444-81-4258293',
        'country' => null,
        'correspondenceCountry' => null,
        'correspondenceEmailSupply' => null,
    ];

    public function testPopulateAndToArrayAreSymmetric()
    {
        $form = new AeCreateForm();
        $form->populate($this->input);

        $this->assertEquals($this->input, $form->toArray(), 'populate and toArray is not symmetric');
    }

    public function testGetters()
    {
        $form = new AeCreateForm();
        $form->populate($this->input);

        $this->assertEquals($this->input['organisationName'], $form->getOrganisationName());
        $this->assertEquals($this->input['organisationType'], $form->getOrganisationType());
        $this->assertEquals($this->input['registeredCompanyNumber'], $form->getRegisteredCompanyNumber());
        $this->assertEquals($this->input['tradingAs'], $form->getTradingAs());
        $this->assertFalse($form->isCorrespondenceContactDetailsSame());
    }

    public function testInitialStateOfObjectIsCorrect()
    {
        $registeredCompanyContactDetails = $this->prepareContactDetails(
            OrganisationContactTypeCode::REGISTERED_COMPANY
        );

        $correspondenceContactDetails = $this->prepareContactDetails(
            OrganisationContactTypeCode::CORRESPONDENCE,
            'correspondence'
        );

        $org = $this->createOrganisation();
        $org->setContacts([$registeredCompanyContactDetails, $correspondenceContactDetails]);

        $form = new AeCreateForm($org);

        $this->assertEquals(
            $this->input,
            $form->toArray(),
            'Invalid initial state of object after populating from Organisation object'
        );
    }

    private function createOrganisation()
    {
        $org = new OrganisationDto();
        $org->setName($this->input['organisationName']);
        $org->setTradingAs($this->input['tradingAs']);
        $org->setOrganisationType($this->input['organisationType']);
        $org->setRegisteredCompanyNumber($this->input['registeredCompanyNumber']);

        return $org;
    }

    private function createAddress($prefix)
    {
        $address = new AddressDto();
        $address->setAddressLine1($this->input[$this->toLowerCamelCase($prefix, 'addressLine1')]);
        $address->setAddressLine2($this->input[$this->toLowerCamelCase($prefix, 'addressLine2')]);
        $address->setAddressLine3($this->input[$this->toLowerCamelCase($prefix, 'addressLine3')]);
        $address->setTown($this->input[$this->toLowerCamelCase($prefix, 'town')]);
        $address->setPostcode($this->input[$this->toLowerCamelCase($prefix, 'postcode')]);

        return $address;
    }

    private function createEmail($prefix)
    {
        $email = new EmailDto();
        $email
            ->setEmail($this->input[$this->toLowerCamelCase($prefix, 'email')])
            ->setIsPrimary(true);

        return $email;
    }

    private function createPhone($prefix)
    {
        $phone = new PhoneDto();
        $phone
            ->setNumber($this->input[$this->toLowerCamelCase($prefix, 'phoneNumber')])
            ->setContactType(PhoneContactTypeCode::BUSINESS)
            ->setIsPrimary(true);

        return $phone;
    }

    private function createFax($prefix)
    {
        $fax = new PhoneDto();
        $fax
            ->setNumber($this->input[$this->toLowerCamelCase($prefix, 'faxNumber')])
            ->setContactType(PhoneContactTypeCode::FAX)
            ->setIsPrimary(true);

        return $fax;
    }

    private function createContactDetails($organisationContactTypeName, $address, $email, $phone, $fax)
    {
        $contactDetails = new OrganisationContactDto();
        $contactDetails->setType($organisationContactTypeName);
        $contactDetails->setAddress($address);
        $contactDetails->setEmails([$email]);
        $contactDetails->setPhones([$phone, $fax]);

        return $contactDetails;
    }

    private function prepareContactDetails($organisationContactTypeName, $prefix = '')
    {
        $address = $this->createAddress($prefix);
        $email = $this->createEmail($prefix);
        $phone = $this->createPhone($prefix);
        $fax = $this->createFax($prefix);

        $contactDetails = $this->createContactDetails(
            $organisationContactTypeName,
            $address,
            $email,
            $phone,
            $fax
        );

        return $contactDetails;
    }

    private function toLowerCamelCase($prefix, $input)
    {
        if (empty($prefix)) {
            return $input;
        }

        return $prefix . ucfirst($input);
    }
}
