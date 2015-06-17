<?php

namespace DvsaCommonTest\Dto\Contact;

use DvsaCommon\Dto\Contact\PhoneDto;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommonTest\Dto\AbstractDtoTester;

/**
 * Unit tests for PhoneDto
 */
class PhoneDtoTest extends AbstractDtoTester
{
    protected $dtoClassName = PhoneDto::class;

    const ID = 1;
    const CONTACT_TYPE = 'personal';
    const NUMBER = '21345213453';
    const IS_PRIMARY = false;

    public function testFromToArray()
    {
        $source = [
            'id'        => self::ID,
            'number'    => self::NUMBER,
            'isPrimary' => self::IS_PRIMARY,
            'type'      => self::CONTACT_TYPE,
        ];

        //  --  test from   --
        $this->assertEquals(self::getDtoObject(), PhoneDto::fromArray($source));

        //  --  test to --
        $this->assertEquals(self::getDtoObject()->toArray(), $source);
    }

    public function testIsEquals()
    {
        $dto = (new PhoneDto())
            ->setContactType(PhoneContactTypeCode::BUSINESS)
            ->setNumber('23423432423')
            ->setIsPrimary(true);

        $dtoB = clone $dto;

        //  --  test equals   --
        $this->assertTrue(PhoneDto::isEquals($dto, $dtoB));
        $this->assertTrue(PhoneDto::isEquals(null, null));

        //  --  test not equals   --
        $dtoB->setNumber('1111');
        $this->assertFalse(PhoneDto::isEquals($dto, $dtoB));

        $dtoB = clone $dto;
        $dtoB->setIsPrimary(false);
        $this->assertFalse(PhoneDto::isEquals($dto, $dtoB));

        $dtoB = clone $dto;
        $dtoB->setContactType(PhoneContactTypeCode::PERSONAL);
        $this->assertFalse(PhoneDto::isEquals($dto, $dtoB));
    }

    /**
     * @return PhoneDto
     */
    public static function getDtoObject()
    {
        return (new PhoneDto())
            ->setId(self::ID)
            ->setContactType(self::CONTACT_TYPE)
            ->setNumber(self::NUMBER)
            ->setIsPrimary(self::IS_PRIMARY);
    }
}
