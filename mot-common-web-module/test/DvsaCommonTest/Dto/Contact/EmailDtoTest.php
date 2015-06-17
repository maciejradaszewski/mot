<?php

namespace DvsaCommonTest\Dto\Contact;

use DvsaCommon\Dto\Contact\EmailDto;
use DvsaCommonTest\Dto\AbstractDtoTester;

/**
 * Unit tests for EmailDto
 */
class EmailDtoTest extends AbstractDtoTester
{
    protected $dtoClassName = EmailDto::class;

    const ID = 1;
    const E_MAIL = 'aaa@wp.pl';
    const IS_PRIMARY = false;

    public function testFromToArray()
    {
        $source = [
            'id'        => self::ID,
            'email'     => self::E_MAIL,
            'isPrimary' => self::IS_PRIMARY,
        ];

        //  --  test from   --
        $this->assertEquals(self::getDtoObject(), EmailDto::fromArray($source));

        //  --  test to --
        $this->assertEquals(self::getDtoObject()->toArray(), $source);
    }

    public function testIsEquals()
    {
        $dto = (new EmailDto())
            ->setEmail('aaa@domain.com')
            ->setIsPrimary(true);

        $dtoB = clone $dto;

        //  --  test equals   --
        $this->assertTrue(EmailDto::isEquals($dto, $dtoB));

        //  --  test not equals   --
        $dtoB->setEmail('bbbb@domain.com');
        $this->assertFalse(EmailDto::isEquals($dto, $dtoB));

        $dtoB = clone $dto;
        $dtoB->setIsPrimary(false);
        $this->assertFalse(EmailDto::isEquals($dto, $dtoB));
    }

    /**
     * @return EmailDto
     */
    public static function getDtoObject()
    {
        return (new EmailDto())
            ->setId(self::ID)
            ->setEmail(self::E_MAIL)
            ->setIsPrimary(self::IS_PRIMARY);
    }
}
