<?php

namespace DashboardTest\Model;

use Dashboard\Model\PersonalDetails;

/**
 * Class PersonalDetailsTest
 *
 * @package DashboardTest\Model
 */
class PersonalDetailsTest extends \PHPUnit_Framework_TestCase
{
    const ID = 1;
    const FIRST_NAME = 'John';
    const MIDDLE_NAME = 'Steven';
    const SURNAME = 'Smith';
    const DOB = '1980-10-10';
    const TITLE = 'Mr';
    const GENDER = 'Male';
    const ADDR_1 = 'London';
    const ADDR_2 = 'Abc';
    const ADDR_3 = '213';
    const TOWN = 'Dublin';
    const POSTCODE = 'LON 123';
    const EMAIL = 'j.smith@example.com';
    const PHONE = '123456765432';
    const DRIVING_LICENCE_BUMBER = '2343213';
    const REGION = 'Other';
    const ROLE_TESTER = 'tester';
    const POSITIONS = 'test';
    const USERNAME = 'tester1';

    public function test_gettersSetters()
    {
        $personalDetails = new PersonalDetails(self::getData());

        $this->assertEquals(self::ID, $personalDetails->getId());
        $this->assertEquals(self::FIRST_NAME, $personalDetails->getFirstName());
        $this->assertEquals(self::USERNAME, $personalDetails->getUsername());
        $this->assertEquals(self::MIDDLE_NAME, $personalDetails->getMiddleName());
        $this->assertEquals(self::SURNAME, $personalDetails->getSurname());
        $this->assertEquals(self::DOB, $personalDetails->getDateOfBirth());
        $this->assertEquals(self::TITLE, $personalDetails->getTitle());
        $this->assertEquals(self::GENDER, $personalDetails->getGender());
        $this->assertEquals(self::ADDR_1, $personalDetails->getAddressLine1());
        $this->assertEquals(self::ADDR_2, $personalDetails->getAddressLine2());
        $this->assertEquals(self::ADDR_3, $personalDetails->getAddressLine3());
        $this->assertEquals(self::TOWN, $personalDetails->getTown());
        $this->assertEquals(self::POSTCODE, $personalDetails->getPostcode());
        $this->assertEquals(self::EMAIL, $personalDetails->getEmail());
        $this->assertEquals(self::PHONE, $personalDetails->getPhoneNumber());
        $this->assertEquals(self::DRIVING_LICENCE_BUMBER, $personalDetails->getDrivingLicenceNumber());
        $this->assertEquals(self::REGION, $personalDetails->getDrivingLicenceRegion());
        $this->assertEquals([ self::POSITIONS ], $personalDetails->getPositions());
        $this->assertCount(1, $personalDetails->getRoles());
        $this->assertEquals(self::ROLE_TESTER, $personalDetails->getRoles()[0]);
    }

    public static function getData()
    {
        return [
            'id'                   => self::ID,
            'firstName'            => self::FIRST_NAME,
            'middleName'           => self::MIDDLE_NAME,
            'surname'              => self::SURNAME,
            'dateOfBirth'          => self::DOB,
            'username'             => self::USERNAME,
            'title'                => self::TITLE,
            'gender'               => self::GENDER,
            'addressLine1'         => self::ADDR_1,
            'addressLine2'         => self::ADDR_2,
            'addressLine3'         => self::ADDR_3,
            'town'                 => self::TOWN,
            'postcode'             => self::POSTCODE,
            'email'                => self::EMAIL,
            'phone'                => self::PHONE,
            'drivingLicenceNumber' => self::DRIVING_LICENCE_BUMBER,
            'drivingLicenceRegion' => self::REGION,
            'roles'                => [
                self::ROLE_TESTER
            ],
            'positions'            => [
                'test'
            ]
        ];
    }
}
