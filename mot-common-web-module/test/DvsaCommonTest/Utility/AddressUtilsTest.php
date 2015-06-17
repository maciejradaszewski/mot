<?php

namespace DvsaCommonTest\Utility;

use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Utility\AddressUtils;

/**
 * Class AddressUtilsTest
 *
 * @package DvsaCommonTest\Utility
 */
class AddressUtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test stringify address passed as array
     */
    public function testStringifyAddressFromArray()
    {
        $address = [
            'town'         => 'Town',
            'addressLine1' => 'addr l1',
            'addressLine2' => 'addr l2',
            'addressLine3' => '',
            'addressLine4' => 'addr l4',
            'exceedField'  => 'Should not appear',
            'postcode'     => 'POST CODE',
        ];

        $this->assertEquals(
            'addr l1, addr l2, addr l4, Town, POST CODE',
            AddressUtils::stringify($address)
        );

        $this->assertEquals(
            'addr l1|addr l2|addr l4|Town|POST CODE',
            AddressUtils::stringify($address, '|')
        );
    }

    /**
     * Test stringify address passed as array
     */
    public function testStringifyAddressFromEntity()
    {
        /** @var AddressDto $address */
        $address = new AddressDto();
        $address
            ->setAddressLine1('addr l1')
            ->setAddressLine2('addr l2')
            ->setTown('Town')
            ->setAddressLine3('')
            ->setPostcode('POST CODE');

        $this->assertEquals(
            'addr l1, addr l2, Town, POST CODE',
            AddressUtils::stringify($address)
        );

        $this->assertEquals(
            'addr l1|addr l2|Town|POST CODE',
            AddressUtils::stringify($address, '|')
        );
    }
}
