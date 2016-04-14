<?php

namespace DvsaCommonTest\Auth\Assertion;

use DvsaCommon\Auth\Assertion\RemoveMotTestingCertificateAssertion;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Auth\MotIdentityInterface;
use DvsaCommon\Enum\VehicleClassGroupCode;
use DvsaCommon\Model\TesterAuthorisation;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommon\Model\TesterGroupAuthorisationStatus;
use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode;
use DvsaCommonTest\TestUtils\XMock;

class RemoveMotTestingCertificateAssertionTest extends \PHPUnit_Framework_TestCase
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

    /** @return RemoveMotTestingCertificateAssertion */
    private function createAssertion()
    {
        return new RemoveMotTestingCertificateAssertion(
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
            new TesterAuthorisation($groupAStatus, $groupBStatus)
        );

        $this->assertTrue($isGranted);
    }

    public function testisGrantedReturnsFalseWhenUserTryRemoveCertificateForAnotherUserAndHasNoPermission()
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
            new TesterAuthorisation()
        );

        $this->assertFalse($isGranted);
    }

    /** @expectedException \DvsaCommon\Exception\UnauthorisedException */
    public function testAssertGrantedThrowsExceptionWhenUserTryRemoveCertificateForAnotherUserAndHasNoPermission()
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
            new TesterAuthorisation()
        );
    }

    /**
     * @dataProvider getInvalidTesterGroupAuthorisationStatus
     */
    public function testIsGrantedReturnFalseWhenPersonHasInvalidQualificationStatus($personId, $vehicleClassGroupCode, TesterGroupAuthorisationStatus $groupAStatus = null, TesterGroupAuthorisationStatus $groupBStatus = null)
    {
        $isGranted = $this->createAssertion()->isGranted(
            $personId,
            $vehicleClassGroupCode,
            new TesterAuthorisation($groupAStatus, $groupBStatus)
        );

        $this->assertFalse($isGranted);
    }

    /**
     * @expectedException \DvsaCommon\Exception\UnauthorisedException
     * @dataProvider getInvalidTesterGroupAuthorisationStatus
     */
    public function testAssertGrantedThrowsExceptionWhenPersonHasInvalidQualificationStatus($personId, $vehicleClassGroupCode, TesterGroupAuthorisationStatus $groupAStatus = null, TesterGroupAuthorisationStatus $groupBStatus = null)
    {
        $this->createAssertion()->assertGranted(
            $personId,
            $vehicleClassGroupCode,
            new TesterAuthorisation($groupAStatus, $groupBStatus)
        );
    }

    public function getInvalidTesterGroupAuthorisationStatus()
    {
        $data = [];
        foreach ($this->getVehicleClassGroupCodes() as $group) {
            foreach (AuthorisationForTestingMotStatusCode::getAll() as $status) {
                if ($status === AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED ||
                    $status === AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED
                ) {
                    continue;
                }

                $data = array_merge(
                    $data,
                    $this->getStatuses(self::USER_ID, $group, $status),
                    $this->getStatuses(self::PERSON_ID, $group, $status)
                );
            }

            $data = array_merge(
                $data,
                [[self::USER_ID, $group, null, null], [self::PERSON_ID, $group, null, null]]
            );
        }

        return $data;
    }

    public function getValidTesterGroupAuthorisationStatus()
    {
        return array_merge(
            $this->getStatuses(self::USER_ID, VehicleClassGroupCode::BIKES, AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED),
            $this->getStatuses(self::USER_ID, VehicleClassGroupCode::BIKES, AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED),
            $this->getStatuses(self::USER_ID, VehicleClassGroupCode::CARS_ETC, AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED),
            $this->getStatuses(self::USER_ID, VehicleClassGroupCode::CARS_ETC, AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED),
            $this->getStatuses(self::PERSON_ID, VehicleClassGroupCode::BIKES, AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED),
            $this->getStatuses(self::PERSON_ID, VehicleClassGroupCode::BIKES, AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED),
            $this->getStatuses(self::PERSON_ID, VehicleClassGroupCode::CARS_ETC, AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED),
            $this->getStatuses(self::PERSON_ID, VehicleClassGroupCode::CARS_ETC, AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED)
        );
    }

    private function getStatuses($userId, $group, $status)
    {
        $data = [
            [
                $userId,
                $group,
                new TesterGroupAuthorisationStatus($status, "name"),
                new TesterGroupAuthorisationStatus($status, "name"),
            ]
        ];

        if ($group === VehicleClassGroupCode::BIKES) {
            $data[] = [
                $userId,
                $group,
                new TesterGroupAuthorisationStatus($status, "name"),
                null
            ];
        }

        if ($group === VehicleClassGroupCode::CARS_ETC) {
            $data[] = [
                $userId,
                $group,
                null,
                new TesterGroupAuthorisationStatus($status, "name"),
            ];
        }

        return $data;
    }

    private function getVehicleClassGroupCodes()
    {
        return [
            VehicleClassGroupCode::BIKES,
            VehicleClassGroupCode::CARS_ETC
        ];
    }
}
