<?php

namespace DvsaCommonTest\Dto\Contact;

use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Contact\ContactDto;
use DvsaCommon\Dto\Contact\EmailDto;
use DvsaCommon\Dto\Contact\PhoneDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Enum\PhoneContactTypeCode;

/**
 * Unit tests for ContactDto
 */
class ContactDtoTest extends \PHPUnit_Framework_TestCase
{
    /** @var  EmailDto */
    private static $emailPrimaryDto;
    /** @var  EmailDto */
    private static $emailDto;
    /** @var  PhoneDto */
    private static $phonePrimaryDto;
    /** @var  PhoneDto */
    private static $phoneSecondaryDto;
    /** @var  PhoneDto */
    private static $faxDto;
    /** @var  AddressDto */
    private static $addressDto;

    private static $phones;
    private static $emails;
    private static $type;

    public function testSettersGetters()
    {
        self::initStatic();

        $contact = self::getDtoObject();

        $this->assertSame(self::$type, $contact->getType());
        $this->assertSame(self::$emailPrimaryDto->getEmail(), $contact->getPrimaryEmailAddress());
        $this->assertSame(self::$phonePrimaryDto->getNumber(), $contact->getPrimaryPhoneNumber());
        $this->assertSame(self::$faxDto->getNumber(), $contact->getPrimaryFaxNumber());
        $this->assertSame(self::$addressDto, $contact->getAddress());
        $this->assertSame(self::$emails, $contact->getEmails());
        $this->assertSame(self::$phones, $contact->getPhones());
    }

    public function testIsEquals()
    {
        $contactDto = self::getDtoObject();

        //  --  is same --
        $contactDto2 = clone $contactDto;
        $this->assertTrue(ContactDto::isEquals($contactDto, $contactDto2));

        //  --  is not same --
        $contactDto2->setAddress((new AddressDto())->setTown('nowhere'));
        $this->assertFalse(ContactDto::isEquals($contactDto, $contactDto2));
    }

    private static function initStatic()
    {
        self::$type = OrganisationContactTypeCode::REGISTERED_COMPANY;
        self::$emailPrimaryDto = EmailDtoTest::getDtoObject()->setIsPrimary(true);
        self::$emailDto = EmailDtoTest::getDtoObject();
        self::$phonePrimaryDto = PhoneDtoTest::getDtoObject()->setIsPrimary(true);
        self::$phoneSecondaryDto = PhoneDtoTest::getDtoObject();
        self::$faxDto = PhoneDtoTest::getDtoObject()->setIsPrimary(true)
            ->setContactType(PhoneContactTypeCode::FAX);

        self::$addressDto = AddressDtoTest::getDtoObject();

        self::$phones = [
            self::$phonePrimaryDto,
            self::$phoneSecondaryDto,
            self::$faxDto,
        ];

        self::$emails = [self::$emailPrimaryDto, self::$emailDto];
    }

    /**
     * @return ContactDto
     */
    public static function getDtoObject()
    {
        $contact = new ContactDto();
        $contact
            ->setType(self::$type)
            ->setAddress(self::$addressDto)
            ->setEmails(self::$emails)
            ->setPhones(self::$phones);

        return $contact;
    }
}
