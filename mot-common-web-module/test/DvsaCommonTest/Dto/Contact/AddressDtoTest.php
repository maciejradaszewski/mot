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
    const POST_CODE = 'CM1 2TQ';
    const TOWN = 'Bristol';

    public function testSettersGetters()
    {
        $address = self::getDtoObject();

        $this->assertEquals(self::LINE_1, $address->getAddressLine1());
        $this->assertEquals(self::LINE_2, $address->getAddressLine2());
        $this->assertEquals(self::LINE_3, $address->getAddressLine3());
        $this->assertEquals(self::TOWN, $address->getTown());
        $this->assertEquals(self::POST_CODE, $address->getPostcode());
    }

    public function testToArray()
    {
        $address = self::getDtoObject();

        $this->assertSame(
            [
                'addressLine1' => $address->getAddressLine1(),
                'addressLine2' => $address->getAddressLine2(),
                'addressLine3' => $address->getAddressLine3(),
                'addressLine4' => $address->getAddressLine4(),
                'town'         => $address->getTown(),
                'country'      => $address->getCountry(),
                'postcode'     => $address->getPostcode(),
            ],
            $address->toArray()
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

    /**
     * @return AddressDto
     */
    public static function getDtoObject()
    {
        return (new AddressDto())->setAddressLine1(self::LINE_1)
            ->setAddressLine2(self::LINE_2)
            ->setAddressLine3(self::LINE_3)
            ->setPostcode(self::POST_CODE)
            ->setTown(self::TOWN);
    }
}
