<?php

namespace DvsaCommonTest\Dto\Person;

use DvsaCommon\Dto\Account\AuthenticationMethodDto;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Person\PersonHelpDeskProfileDto;
use DvsaCommon\Enum\LicenceCountryCode;
use DvsaCommon\Enum\SiteBusinessRoleCode;

/**
 * Unit tests for PersonHelpDeskProfileDto
 */
class PersonHelpDeskProfileDtoTest extends \PHPUnit_Framework_TestCase
{
    public function testFromArrayWithValidArrayReturnsDto()
    {
        $data = $this->getPopulatedArray();
        $expected = $this->getPopulatedDto();

        $actual = PersonHelpDeskProfileDto::fromArray($data);

        $this->assertEquals($expected, $actual);
    }

    public function testToArrayWithValidDtoReturnsArray()
    {
        $dto = $this->getPopulatedDto();
        $expected = $this->getPopulatedArray();

        $actual = $dto->toArray();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return PersonHelpDeskProfileDto
     */
    private function getPopulatedDto()
    {
        return (new PersonHelpDeskProfileDto())
            ->setTitle('Miss')
            ->setUserName('user')
            ->setFirstName('Test1')
            ->setMiddleName('Test2')
            ->setLastName('Test3')
            ->setDateOfBirth('1992-04-01')
            ->setAddress(
                (new AddressDto())
                    ->setTown('Sm Twn')
                    ->setPostcode('S4U 1T1')
                    ->setAddressLine1('1.')
                    ->setAddressLine2('2.')
                    ->setAddressLine3('3.')
                    ->setAddressLine4('4.')
            )
            ->setTelephone('+768-45-4433630')
            ->setEmail('dummy@email.com')
            ->setRoles([SiteBusinessRoleCode::TESTER])
            ->setDrivingLicenceNumber('1234567890')
            ->setDrivingLicenceRegion(LicenceCountryCode::GREAT_BRITAIN_ENGLAND_SCOTLAND_AND_WALES)
            ->setDrivingLicenceRegionCode('GB')
            ->setAuthenticationMethod(
                (new AuthenticationMethodDto())
                    ->setName("Pin")
                    ->setCode("PIN")
            );
    }

    private function getPopulatedArray()
    {
        return [
            'title' => 'Miss',
            'userName' => 'user',
            'firstName' => 'Test1',
            'middleName' => 'Test2',
            'lastName' => 'Test3',
            'dateOfBirth' => '1992-04-01',
            'postcode' => 'S4U 1T1',
            'addressLine1' => '1.',
            'addressLine2' => '2.',
            'addressLine3' => '3.',
            'addressLine4' => '4.',
            'town' => 'Sm Twn',
            'email' => 'dummy@email.com',
            'telephone' => '+768-45-4433630',
            'roles' => [
                SiteBusinessRoleCode::TESTER,
            ],
            'drivingLicence' => '1234567890',
            'drivingLicenceRegion' => LicenceCountryCode::GREAT_BRITAIN_ENGLAND_SCOTLAND_AND_WALES,
            'drivingLicenceRegionCode' => 'GB',
            'authenticationMethod' => [
                'name' => 'Pin',
                'code' => 'PIN',
            ],
        ];
    }
}
