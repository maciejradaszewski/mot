<?php

namespace SiteTest\Controller;

use CoreTest\Controller\AbstractFrontendControllerTestCase;
use DvsaClient\Entity\Person;
use DvsaClient\Entity\VehicleTestingStation;
use DvsaClient\Mapper\PersonMapper;
use DvsaClient\Mapper\SitePositionMapper;
use DvsaClient\Mapper\SiteRoleMapper;
use DvsaClient\Mapper\VehicleTestingStationMapper;
use DvsaClient\MapperFactory;
use DvsaCommon\Enum\SiteBusinessRoleCode;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\XMock;
use Site\Controller\RoleController;
use Site\Traits\SiteServicesTrait;
use DvsaCommon\Validator\UsernameValidator;

/**
 * Class RoleControllerTest.
 *
 * Testing frontend controller for assigning a role to a person at site level.
 */
class RoleControllerTest extends AbstractFrontendControllerTestCase
{
    const VIEW_MODEL_CLASS_PATH = 'Zend\View\Model\ViewModel';

    private $mapperFactoryMock;
    private $roleMapperMock;
    private $personMapperMock;
    private $positionMapperMock;
    private $vehicleTestingStationMapperMock;
    private $siteId = 1;
    private $personId = 1;
    private $nomineeId = 1;
    private $vehicleTestingStationId = 1;
    private $roleCode = SiteBusinessRoleCode::TESTER;

    public function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->setServiceManager($serviceManager);
        $serviceManager->setAllowOverride(true);

        $usernameValidatorMock = $this->createUsernameValidatorMock(true);
        $htmlPurifier          = $this->getMock('HTMLPurifier');
        $this->controller      = new RoleController($usernameValidatorMock, $htmlPurifier);
        $this->controller->setServiceLocator($serviceManager);

        $this->roleMapperMock = $this->getRoleMapperMock();
        $this->personMapperMock = $this->getPersonMapperMock();
        $this->positionMapperMock = $this->getPositionMapperMock();
        $this->vehicleTestingStationMapperMock = $this->getVehicleTestingStationMapperMock();

        $this->mapperFactoryMock = $this->getMapperFactoryMock(
            $this->personMapperMock,
            $this->roleMapperMock,
            $this->positionMapperMock,
            $this->vehicleTestingStationMapperMock
        );

        $serviceManager->setService(MapperFactory::class, $this->mapperFactoryMock);

        $this->controller->setServiceLocator($serviceManager);

        $this->createHttpRequestForController('role');
    }

    public function testSearchForPersonActionCanBeAccessed()
    {
        $response = $this->getResponseForAction(
            'searchForPerson',
            [
                'vehicleTestingStationId' => $this->siteId,
            ]
        );
        $this->assertEquals(self::HTTP_OK_CODE, $response->getStatusCode());
    }

    public function testSearchForPersonReturnsViewModel()
    {
        $this->routeMatch->setParam('action', 'searchForPerson');
        $this->routeMatch->setParam('vehicleTestingStationId', $this->vehicleTestingStationId);
        $this->request->setMethod('get');

        $viewModelArray = $this->controller->dispatch($this->request);

        $this->assertEquals($viewModelArray['vehicleTestingStationId'], $this->vehicleTestingStationId);
        $this->assertEquals($viewModelArray['form'], []);
        $this->assertEquals($viewModelArray['personId'], '');
        $this->assertEquals($viewModelArray['userNotFound'], false);
        $this->assertInstanceOf(VehicleTestingStation::class, $viewModelArray['vehicleTestingStation']);
    }

    public function testListUserRolesActionCanBeAccessed()
    {
        //   $this->getMockBuilder()
        $response = $this->getResponseForAction(
            'listUserRoles',
            [
                'vehicleTestingStationId' => $this->siteId,
                'personId'                => $this->personId
            ]
        );
        $this->assertEquals(self::HTTP_OK_CODE, $response->getStatusCode());
    }

    public function testAssignConfirmationActionCanBeAccessed()
    {
        $response = $this->getResponseForAction(
            'confirmNomination',
            [
                'nomineeId'               => $this->nomineeId,
                'vehicleTestingStationId' => $this->siteId,
                'roleCode'                => $this->roleCode
            ]
        );

        $this->assertEquals(self::HTTP_OK_CODE, $response->getStatusCode());
    }

    /**
     * @param bool $isValid The value returned by UsernameValidator::isValid()
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createUsernameValidatorMock($isValid = true)
    {
        $usernameValidatorMock = $this
            ->getMockBuilder(UsernameValidator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $usernameValidatorMock
            ->expects($this->any())
            ->method('isValid')
            ->willReturn($isValid);

        if (!$isValid) {
            $messages = ['stringLengthTooLong' => sprintf("Username must be less than %s characters long.",
                self::MAX_USERNAME_LENGTH)];

            $usernameValidatorMock
                ->expects($this->any())
                ->method('getMessages')
                ->willReturn($messages);
        }

        return $usernameValidatorMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     * @throws \Exception
     */
    private function getRoleMapperMock()
    {
        $roleMapperMock = $this->roleMapperMock = XMock::of(SiteRoleMapper::class);

        $roles = [
            SiteBusinessRoleCode::SITE_MANAGER,
            SiteBusinessRoleCode::SITE_ADMIN,
            SiteBusinessRoleCode::TESTER,
        ];

        $roleMapperMock->expects($this->any())
            ->method('fetchAllForPerson')
            ->with($this->siteId, $this->personId)
            ->will($this->returnValue($roles));

        return $roleMapperMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     * @throws \Exception
     */
    private function getPositionMapperMock()
    {
        $positionMapperMock = $this->positionMapperMock = XMock::of(SitePositionMapper::class);

        $positionMapperMock->expects($this->any())
            ->method('postJson')
            ->with($this->siteId, $this->nomineeId, $this->roleCode);

        return $positionMapperMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     * @throws \Exception
     */
    private function getPersonMapperMock()
    {
        $personMapperMock = XMock::of(PersonMapper::class);

        $person = new Person();

        $personMapperMock->expects($this->any())
            ->method('getById')
            ->with($this->personId)
            ->will($this->returnValue($person));

        $personMapperMock->expects($this->any())
            ->method('getByIdentifier')
            ->with($this->personId)
            ->will($this->returnValue($person));

        return $personMapperMock;
    }

    private function getVehicleTestingStationMapperMock() {
        $vehicleTestingStationMapperMock = XMock::of(VehicleTestingStationMapper::class);

        $vehicleTestingStation = new VehicleTestingStation();

        $vehicleTestingStationMapperMock->expects($this->any())
            ->method('getById')
            ->with($this->vehicleTestingStationId)
            ->will($this->returnValue($vehicleTestingStation));

        return $vehicleTestingStationMapperMock;
    }

    /**
     * @param $personMapperMock
     * @param $roleMapperMock
     * @param $positionMapperMock
     * @return \PHPUnit_Framework_MockObject_MockObject
     * @throws \Exception
     */
    private function getMapperFactoryMock($personMapperMock, $roleMapperMock, $positionMapperMock,
                                          $vehicleTestingStationMock)
    {
        $mapperFactoryMock = XMock::of(MapperFactory::class);

        $map = [
            ['Person', $personMapperMock],
            ['SiteRole', $roleMapperMock],
            ['SitePosition', $positionMapperMock],
            ['VehicleTestingStation', $vehicleTestingStationMock],
        ];

        $mapperFactoryMock->expects($this->any())
            ->method('__get')
            ->will($this->returnValueMap($map));

        return $mapperFactoryMock;
    }
}
