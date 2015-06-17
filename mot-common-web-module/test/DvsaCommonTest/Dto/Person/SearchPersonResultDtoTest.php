<?php

namespace DvsaCommonTest\Dto\Person;

use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Person\SearchPersonResultDto;

/**
 * Unit tests for SearchPersonResultDto
 */
class SearchPersonResultDtoTest extends \PHPUnit_Framework_TestCase
{
    const ID = 1919;
    const FIRST_NAME = 'Zdzislaw';
    const LAST_NAME = 'Kowalski';
    const MIDDLE_NAME = 'Thomas';
    const DATE_OF_BIRTH = '1980-10-10';

    public function testGetterSetter()
    {
        $dto = new SearchPersonResultDto(self::getSearchPersonResultDtoData());

        $this->assertEquals(self::ID, $dto->getPersonId());
        $this->assertEquals(self::FIRST_NAME, $dto->getFirstName());
        $this->assertEquals(self::LAST_NAME, $dto->getLastName());
        $this->assertEquals(self::MIDDLE_NAME, $dto->getMiddleName());
        $this->assertEquals(self::DATE_OF_BIRTH, $dto->getDateOfBirth());
        $this->assertInstanceOf(AddressDto::class, $dto->getAddress());
    }

    public function test_getList_emptyArray_shouldReturnEmptyArray()
    {
        $list = SearchPersonResultDto::getList([]);
        $this->assertCount(0, $list);
    }

    public function test_getList_arrayWithOneElement_shouldReturnArrayWithOneDtoObject()
    {
        $list = SearchPersonResultDto::getList([self::getSearchPersonResultDtoData()]);
        $this->assertCount(1, $list);
        $this->assertInstanceOf(SearchPersonResultDto::class, $list[0]);
    }

    public function test_getList_arrayManyElements_shouldReturnArrayOfDtoObjects()
    {
        $list = SearchPersonResultDto::getList(
            [
                self::getSearchPersonResultDtoData(),
                self::getSearchPersonResultDtoData(),
                self::getSearchPersonResultDtoData()
            ]
        );
        $this->assertCount(3, $list);
        $this->assertInstanceOf(SearchPersonResultDto::class, $list[0]);
        $this->assertInstanceOf(SearchPersonResultDto::class, $list[1]);
        $this->assertInstanceOf(SearchPersonResultDto::class, $list[2]);
    }

    public static function getSearchPersonResultDtoData()
    {
        return [
            'id'           => self::ID,
            'firstName'    => self::FIRST_NAME,
            'lastName'     => self::LAST_NAME,
            'middleName'   => self::MIDDLE_NAME,
            'dateOfBirth'  => self::DATE_OF_BIRTH,
            'addressLine1' => 1,
            'addressLine2' => 2,
            'addressLine3' => 3,
            'addressLine4' => 4,
            'postcode'     => 'CM1 2DD',
            'town'         => 'Edinburgh',
            'username'     => 'test'
        ];
    }
}
