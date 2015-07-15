<?php

namespace DvsaCommonTest\Dto\Contact;

use DvsaCommon\Dto\Contact\AddressDto;

/**
 * Unit tests for AddressDto
 */
class AddressDtoTest extends \PHPUnit_Framework_TestCase
{
    const LINE_1 = 'line1';
    const LINE_2 = 'line2';
    const LINE_3 = 'line3';
    const LINE_4 = 'line4';
    const POST_CODE = 'CM1 2TQ';
    const TOWN = 'Bristol';
    const COUNTRY = 'test_Country';

    public function testSettersGetters()
    {
        $address = self::getDtoObject();

        $this->assertEquals(self::LINE_1, $address->getAddressLine1());
        $this->assertEquals(self::LINE_2, $address->getAddressLine2());
        $this->assertEquals(self::LINE_3, $address->getAddressLine3());
        $this->assertEquals(self::LINE_4, $address->getAddressLine4());
        $this->assertEquals(self::TOWN, $address->getTown());
        $this->assertEquals(self::POST_CODE, $address->getPostcode());
        $this->assertEquals(self::COUNTRY, $address->getCountry());
    }

    public function testToArray()
    {
        $this->assertSame(
            self::getArray(),
            self::getDtoObject()->toArray()
        );
    }

    public function testFromArray()
    {
        $this->assertEquals(
            self::getDtoObject(),
            AddressDto::fromArray(self::getArray())
        );
    }

    public function testIsEquals()
    {
        $address = self::getDtoObject();

        //  --  is same --
        $address2 = clone $address;
        $this->assertTrue(AddressDto::isEquals($address, $address2));

        //  --  is not same --
        $address2->setAddressLine1('other address');
        $this->assertFalse(AddressDto::isEquals($address, $address2));
    }

    public function testIsEmpty()
    {
        $this->assertTrue((new AddressDto())->isEmpty());
    }

    public function testGetFullAddress()
    {
        $this->assertEquals(
            'line1, line2, line3, line4, Bristol, CM1 2TQ',
            self::getDtoObject()->getFullAddressString()
        );
    }

    /**
     * @return AddressDto
     */
    public static function getDtoObject()
    {
        return (new AddressDto())->setAddressLine1(self::LINE_1)
            ->setAddressLine2(self::LINE_2)
            ->setAddressLine3(self::LINE_3)
            ->setAddressLine4(self::LINE_4)
            ->setPostcode(self::POST_CODE)
            ->setTown(self::TOWN)
            ->setCountry(self::COUNTRY);
    }

    private static function getArray()
    {
        return [
            'addressLine1' => self::LINE_1,
            'addressLine2' => self::LINE_2,
            'addressLine3' => self::LINE_3,
            'addressLine4' => self::LINE_4,
            'town'         => self::TOWN,
            'country'      => self::COUNTRY,
            'postcode'     => self::POST_CODE,
        ];
    }
}
