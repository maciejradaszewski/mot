<?php

namespace DvsaCommonTest\Organisation\Dto;

use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Organisation\AuthorisedExaminerListItemDto;

/**
 * unit tests for AuthorisedExaminerAuthorisationDto
 *
 * @package DvsaCommonTest\Organisation\Dto
 */
class AuthorisedExaminerListItemDtoTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $expectId = 123456789;
        $expectName = 'AE Name';
        $expectType = 'Partnership';
        $expectPhone = '800-8001-9002';

        $addressDto = new AddressDto();
        $addressDto
            ->setAddressLine1('Adress line 1')
            ->setTown('Lunton')
            ->setPostcode('E12 A123');

        //  --  set expected values --
        $checkDto = new AuthorisedExaminerListItemDto();
        $checkDto
            ->setId($expectId)
            ->setName($expectName)
            ->setType($expectType)
            ->setAddress($addressDto)
            ->setPhone($expectPhone);

        //  --  check expected and actial values    --
        $this->assertEquals($expectId, $checkDto->getId());
        $this->assertEquals($expectName, $checkDto->getName());
        $this->assertEquals($expectType, $checkDto->getType());
        $this->assertEquals($expectPhone, $checkDto->getPhone());

        $actualAddressDto = $checkDto->getAddress();
        $this->assertEquals($addressDto->getAddressLine1(), $actualAddressDto->getAddressLine1());
        $this->assertEquals($addressDto->getAddressLine2(), $actualAddressDto->getAddressLine2());
        $this->assertEquals($addressDto->getTown(), $actualAddressDto->getTown());
        $this->assertEquals($addressDto->getPostcode(), $actualAddressDto->getPostcode());
    }
}
