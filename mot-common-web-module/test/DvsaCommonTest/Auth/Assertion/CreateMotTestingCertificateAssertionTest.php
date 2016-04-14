<?php

namespace DvsaCommonTest\Auth\Assertion;

use DvsaCommon\Auth\Assertion\CreateMotTestingCertificateAssertion;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Auth\MotIdentityInterface;
use DvsaCommon\Enum\RoleCode;
use DvsaCommon\Enum\VehicleClassGroupCode;
use DvsaCommon\Model\TesterAuthorisation;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommon\Model\TesterGroupAuthorisationStatus;
use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode;
use DvsaCommonTest\TestUtils\XMock;

class CreateMotTestingCertificateAssertionTest extends \PHPUnit_Framework_TestCase
{
    const USER_ID = 1;
    const PERSON_ID = 2;

    private $authService;
    private $identityProvider;

    public function setUp()
    {
        $this->authService = XMock::of(MotAuthorisationServiceInterface::class);
        $this->identityProvider = XMock::of(MotIdentityProviderInterface::class);

        $identity = XMock::of(MotIdentityInterface::class);
        $identity
            ->expects($this->any())
            ->method("getUserId")
            ->willReturn(self::USER_ID);

        $this
            ->identityProvider
            ->expects($this->any())
            ->method("getIdentity")
            ->willReturn($identity)
        ;
    }

    /** @return  CreateMotTestingCertificateAssertion */
    private function createAssertion()
    {
        return new CreateMotTestingCertificateAssertion(
            $this->authService,
            $this->identityProvider
        );
    }

    /**
     * @dataProvider getValidTesterGroupAuthorisationStatus
     */
    public function testIsGrantedReturnsTrueForValidData($personId, $vehicleClassGroupCode, TesterGroupAuthorisationStatus $groupAStatus = null, TesterGroupAuthorisationStatus $groupBStatus = null)
    {
        $isGranted = $this->createAssertion()->isGranted(
            $personId,
            $vehicleClassGroupCode,
            [],
            new TesterAuthorisation($groupAStatus, $groupBStatus)
        );

        $this->assertTrue($isGranted);
    }

    public function testisGrantedReturnsFalseWhenUserTryCreateCertificateForAnotherUserAndHasNoPermission()
    {
        $this
            ->authService
            ->expects($this->atLeastOnce())
            ->method("assertGranted")
            ->willThrowException(new UnauthorisedException(""))
            ;

        $isGranted = $this->createAssertion()->isGranted(
            self::PERSON_ID,
            VehicleClassGroupCode::BIKES,
            [],
            new TesterAuthorisation()
        );

        $this->assertFalse($isGranted);
    }

    /** @expectedException \DvsaCommon\Exception\UnauthorisedException */
    public function testAssertGrantedThrowsExceptionWhenUserTryCreateCertificateForAnotherUserAndHasNoPermission()
    {
        $this
            ->authService
            ->expects($this->atLeastOnce())
            ->method("assertGranted")
            ->willThrowException(new UnauthorisedException(""))
        ;

        $this->createAssertion()->assertGranted(
            self::PERSON_ID,
            VehicleClassGroupCode::BIKES,
            [],
            new TesterAuthorisation()
        );
    }

    public function testisGrantedReturnsFalseWhenUserPersonHasDvsaRole()
    {
        $isGranted = $this->createAssertion()->isGranted(
            self::USER_ID,
            VehicleClassGroupCode::BIKES,
            [RoleCode::AREA_OFFICE_1],
            new TesterAuthorisation()
        );

        $this->assertFalse($isGranted);
    }

    /** @expectedException \DvsaCommon\Exception\UnauthorisedException */
    public function testAssertGrantedThrowsExceptionWhenUserPersonHasDvsaRole()
    {
        $this->createAssertion()->assertGranted(
            self::USER_ID,
            VehicleClassGroupCode::BIKES,
            [RoleCode::AREA_OFFICE_1],
            new TesterAuthorisation()
        );
    }

    /**
     * @dataProvider getInvalidTesterGroupAuthorisationStatus
     */
    public function testIsGrantedReturnFalseWhenUserPersonHasInvalidQualificationStatus($group, TesterGroupAuthorisationStatus $groupAStatus = null, TesterGroupAuthorisationStatus $groupBStatus = null)
    {
        $isGranted = $this->createAssertion()->isGranted(
            self::USER_ID,
            $group,
            [],
            new TesterAuthorisation($groupAStatus, $groupBStatus)
        );

        $this->assertFalse($isGranted);
    }

    /**
     * @expectedException \DvsaCommon\Exception\UnauthorisedException
     * @dataProvider getInvalidTesterGroupAuthorisationStatus
     */
    public function testAssertGrantedThrowsExceptionWhenUserPersonHasInvalidQualificationStatus($group, TesterGroupAuthorisationStatus $groupAStatus = null, TesterGroupAuthorisationStatus $groupBStatus = null)
    {
        $this->createAssertion()->assertGranted(
            self::USER_ID,
            $group,
            [],
            new TesterAuthorisation($groupAStatus, $groupBStatus)
        );
    }

    public function getInvalidTesterGroupAuthorisationStatus()
    {
        $data = [];
        foreach ($this->getVehicleClassGroupCodes() as $groupCode) {
            $data += $this->getInvalidTesterGroupAuthorisationStatusForFroup($groupCode);
        }

        return $data;
    }

    private function getInvalidTesterGroupAuthorisationStatusForFroup($group)
    {
        $data = [];

        foreach (AuthorisationForTestingMotStatusCode::getAll() as $status) {
            if (AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED === $status) {
                continue;
            }

            $data[] = [
                $group,
                ($group === VehicleClassGroupCode::BIKES)? new TesterGroupAuthorisationStatus($status, "name") : null,
                ($group === VehicleClassGroupCode::BIKES)? new TesterGroupAuthorisationStatus($status, "name") : null
            ];
            $data[] = [
                $group,
                ($group === VehicleClassGroupCode::CARS_ETC)? null : new TesterGroupAuthorisationStatus($status, "name"),
                ($group === VehicleClassGroupCode::CARS_ETC)? new TesterGroupAuthorisationStatus($status, "name") : null
            ];
        }

        return $data;
    }

    public function getValidTesterGroupAuthorisationStatus()
    {
        return [
            [
                self::USER_ID,
                VehicleClassGroupCode::BIKES,
                null,
                null,
            ],
            [
                self::USER_ID,
                VehicleClassGroupCode::CARS_ETC,
                null,
                null,
            ],
            [
                self::USER_ID,
                VehicleClassGroupCode::BIKES,
                new TesterGroupAuthorisationStatus(AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED, "name"),
                null
            ],
            [
                self::USER_ID,
                VehicleClassGroupCode::CARS_ETC,
                null,
                new TesterGroupAuthorisationStatus(AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED, "name"),
            ],
            [
                self::USER_ID,
                VehicleClassGroupCode::BIKES,
                new TesterGroupAuthorisationStatus(AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED, "name"),
                new TesterGroupAuthorisationStatus(AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED, "name"),
            ],
            [
                self::USER_ID,
                VehicleClassGroupCode::CARS_ETC,
                new TesterGroupAuthorisationStatus(AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED, "name"),
                new TesterGroupAuthorisationStatus(AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED, "name"),
            ],
            [
                self::PERSON_ID,
                VehicleClassGroupCode::BIKES,
                new TesterGroupAuthorisationStatus(AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED, "name"),
                null
            ],
            [
                self::PERSON_ID,
                VehicleClassGroupCode::CARS_ETC,
                null,
                new TesterGroupAuthorisationStatus(AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED, "name"),
            ],
            [
                self::PERSON_ID,
                VehicleClassGroupCode::BIKES,
                new TesterGroupAuthorisationStatus(AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED, "name"),
                new TesterGroupAuthorisationStatus(AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED, "name"),
            ],
            [
                self::PERSON_ID,
                VehicleClassGroupCode::CARS_ETC,
                new TesterGroupAuthorisationStatus(AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED, "name"),
                new TesterGroupAuthorisationStatus(AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED, "name"),
            ],
            [
                self::PERSON_ID,
                VehicleClassGroupCode::BIKES,
                null,
                null,
            ],
            [
                self::PERSON_ID,
                VehicleClassGroupCode::CARS_ETC,
                null,
                null,
            ],
        ];
    }

    private function getVehicleClassGroupCodes()
    {
        return [
            VehicleClassGroupCode::BIKES,
            VehicleClassGroupCode::CARS_ETC
        ];
    }
}
