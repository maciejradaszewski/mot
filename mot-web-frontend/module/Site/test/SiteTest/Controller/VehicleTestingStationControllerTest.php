<?php

namespace SiteTest\Controller;

use Application\Service\CatalogService;
use CoreTest\Controller\AbstractFrontendControllerTestCase;
use DvsaClient\Mapper\EquipmentMapper;
use DvsaClient\Mapper\MotTestInProgressMapper;
use DvsaClient\Mapper\VehicleTestingStationDtoMapper;
use DvsaClient\Mapper\VehicleTestingStationMapper;
use DvsaClient\MapperFactory;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Enum\EquipmentModelStatusCode;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommon\HttpRestJson\Client;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilderWeb;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\Controller\StubIdentityAdapter;
use DvsaCommonTest\TestUtils\XMock;
use Site\Controller\VehicleTestingStationController;
use Site\Form\VtsContactDetailsUpdateForm;
use PHPUnit_Framework_MockObject_MockObject as MockObj;

/**
 * Class VehicleTestingStationControllerTest
 *
 * Tests for adding and editing VTS and viewing overview
 */
class VehicleTestingStationControllerTest extends AbstractFrontendControllerTestCase
{
    const SITE_ID = 9;
    const SITE_NR = 'S000001';
    const SITE_NAME = 'Site Name';

    private $mapperFactoryMock;
    /** @var  VehicleTestingStationMapper|MockObj */
    private $mockVtsDtoMapper;

    /**
     * Test has user access to page or not with/out auth and permission
     *
     * @param string  $action          Request action
     * @param array   $params          Action parameters
     * @param array   $permissions     User has permissions
     * @param boolean $expectCanAccess Expect user has or not access to page
     *
     * @dataProvider dataProviderTestCanAccessHasRight
     */
    public function testCanAccessHasRight(
        $action,
        $params = [],
        $permissions = [],
        $expectCanAccess,
        $expectException = 'Exception',
        $expectErrMsg = null
    ) {
        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asTester());
        $this->setupAuthorizationService($permissions);

        if (!$expectCanAccess) {
            $this->setExpectedException($expectException, ($expectErrMsg ? $expectErrMsg : ''));
        }

        $this->getResponseForAction($action, $params);

        if (!$expectCanAccess) {
            $this->assertResponseStatus(self::HTTP_REDIRECT_CODE);
        } else {
            $this->assertResponseStatus(self::HTTP_OK_CODE);
        }
    }

    public function dataProviderTestCanAccessHasRight()
    {
        return [
            ['index', ['id' => self::SITE_ID], [], true],
            ['index', ['sitenumber' => self::SITE_NR], [], true],
            ['create', [], [], true],
            ['edit', ['id' => self::SITE_ID], [PermissionAtSite::VTS_UPDATE_NAME], true],
            [
                'action'          => 'index',
                'params'          => ['id' => null],
                'permissions'     => [],
                'expectCanAccess' => false,
                'expectException' => \Exception::class,
                'expectErrMsg'    => VehicleTestingStationController::ERR_MSG_INVALID_SITE_ID_OR_NR,
            ],
            [
                'action'          => 'index',
                'params'          => ['sitenumber' => null],
                'permissions'     => [],
                'expectCanAccess' => false,
                'expectException' => \Exception::class,
                'expectErrMsg'    => VehicleTestingStationController::ERR_MSG_INVALID_SITE_ID_OR_NR,
            ],
            [
                'action'          => 'configureBrakeTestDefaults',
                'params'          => ['id' => null],
                'permissions'     => [],
                'expectCanAccess' => false,
                'expectException' => 'Exception',
                'expectErrMsg'    => VehicleTestingStationController::ERR_MSG_INVALID_SITE_ID_OR_NR,
            ],
            ['configureBrakeTestDefaults', ['id' => self::SITE_ID], [], false, UnauthorisedException::class],
            [
                'action'          => 'configureBrakeTestDefaults',
                'params'          => ['id' => self::SITE_ID],
                'permissions'     => [PermissionAtSite::DEFAULT_BRAKE_TESTS_CHANGE],
                'expectCanAccess' => true,
            ],
            [
                'action'          => 'contactDetails',
                'params'          => ['id' => null],
                'permissions'     => [],
                'expectCanAccess' => false,
                'expectException' => \Exception::class,
                'expectErrMsg'    => VehicleTestingStationController::ERR_MSG_INVALID_SITE_ID_OR_NR,
            ],
            ['contactDetails', ['id' => self::SITE_ID], [], false, UnauthorisedException::class],
            [
                'action'          => 'contactDetails',
                'params'          => ['id' => self::SITE_ID],
                'permissions'     => [PermissionAtSite::VTS_UPDATE_BUSINESS_DETAILS],
                'expectCanAccess' => true,
            ],
        ];
    }

    public function testUpdatePostFormError()
    {
        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asTester());
        $this->setupAuthorizationService([PermissionAtSite::VTS_UPDATE_BUSINESS_DETAILS]);

        $postData = [];

        $result = $this->getResultForAction2('post', 'contactDetails', ['id' => self::SITE_ID], null, $postData);

        $expectErrors = [
            'BUSemail' =>'The email you entered is not valid',
            'BUSPhoneNumber' => 'A telephone number must be entered',
        ];

        /** @var  VtsContactDetailsUpdateForm $form */
        $form = $result->getVariable('form');

        foreach ($expectErrors as $field => $error) {
            $this->assertEquals($error, $form->getError($field));
        }
    }

    /**
     * @dataProvider dataProviderTestUpdatePost
     */
    public function testUpdatePost($postData, $apiReturn, array $expect)
    {
        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asTester());
        $this->setupAuthorizationService([PermissionAtSite::VTS_UPDATE_BUSINESS_DETAILS]);

        //  --  mock    --
        if ($apiReturn !== null) {
            $this->mockMethod($this->mockVtsDtoMapper, 'updateContactDetails', $this->once(), $apiReturn);
        }

        //  --  call    --
        $result = $this->getResultForAction2('post', 'contactDetails', ['id' => self::SITE_ID], null, $postData);

        //  --  check   --
        if (!empty($expect['errors'])) {
            /** @var  VtsContactDetailsUpdateForm $form */
            $form = $result->getVariable('form');

            foreach ($expect['errors'] as $field => $error) {
                $this->assertEquals($error, $form->getError($field));
            }
        } elseif (!empty($expect['url'])) {
            $this->assertRedirectLocation2($expect['url']);
        }
    }

    public function dataProviderTestUpdatePost()
    {
        $postData = [
            'BUSEmail'             => 'test@domain.com',
            'BUSEmailConfirmation' => 'test@domain.com',
            'BUSPhoneNumber'       => '12345678',
        ];

        return [
            //  --  test errors from client  --
            [
                'postData' => [],
                'apiReturn' => null,
                'expect' => [
                    'errors' => [
                        'BUSemail' =>'The email you entered is not valid',
                        'BUSPhoneNumber' => 'A telephone number must be entered',
                    ],
                ],
            ],
            //  --  test errors from api    --
            [
                'postData' => $postData,
                'apiReturn' => new ValidationException(
                    '/', 'post', [], 10, [['field' => 'fieldA', 'displayMessage' => 'error msg']]
                ),
                'expect' => [
                    'errors' => [
                        'fieldA' => 'error msg',
                    ],
                ],
            ],
            //  --  test success    --
            [
                'postData' => $postData,
                'apiReturn' => ['id' => self::SITE_ID],
                'expect' => [
                    'url' => VehicleTestingStationUrlBuilderWeb::byId(self::SITE_ID)->toString(),
                ],
            ],
        ];
    }

    public function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);
        $this->setServiceManager($serviceManager);

        $this->controller = new VehicleTestingStationController();
        $this->controller->setServiceLocator($serviceManager);

        $this->mapperFactoryMock = $this->getMapperFactoryMock();

        $serviceManager->setService(MapperFactory::class, $this->mapperFactoryMock);

        $this->createHttpRequestForController('VehicleTestingStation');
        $serviceManager->setService(Client::class, XMock::of(Client::class));

        $catalogMock = XMock::of(CatalogService::class);
        $catalogMock->expects($this->any())
                    ->method('getEquipmentModelStatuses')
                    ->willReturn([
                        json_decode('"equipmentModelStatus": [
                            {
                                "id": 1,
                                "code": "'. EquipmentModelStatusCode::APPROVED . '",
                                "name": "Approved"
                            },
                            {
                                "id": 2,
                                "code": "'. EquipmentModelStatusCode::NOT_INSTALLABLE . '",
                                "name": "Not Installable"
                            },
                            {
                                "id": 3,
                                "code": "'. EquipmentModelStatusCode::WITHDRAWN . '",
                                "name": "Withdrawn"
                            }
                        ]')
                    ]);

        $serviceManager->setService('CatalogService', $catalogMock);

        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asTester());

        $this->mockUrlPlugin();
    }

    private function getVehicleTestingStationMapperMock()
    {
        $vehicleTestingStationMapperMock = XMock::of(VehicleTestingStationMapper::class);

        $vehicleTestingStationMapperMock->expects($this->any())
            ->method('getById')
            ->with(self::SITE_ID)
            ->will(
                $this->returnValue(
                    [
                        'name'                                  => self::SITE_NAME,
                        'positions'                             => [],
                        'contacts'                              => [],
                        'id'                                    => 1,
                        'siteOpeningHours'                      => [],
                        'defaultBrakeTestClass1And2'            => null,
                        'defaultServiceBrakeTestClass3AndAbove' => null,
                        'defaultParkingBrakeTestClass3AndAbove' => null,
                        'roles'                                 => [],
                        'address' => (new AddressDto())->setAddressLine1('test')
                                                       ->setAddressLine2('test')
                                                       ->setAddressLine3('test')
                                                       ->setPostcode('test')
                                                       ->setTown('test')
                    ]
                )
            );

        $vehicleTestingStationMapperMock->expects($this->any())
            ->method('getBySiteNumber')
            ->with(self::SITE_NR)
            ->will(
                $this->returnValue(
                    [
                        'name'                                  => self::SITE_NAME,
                        'positions'                             => [],
                        'contacts'                              => [],
                        'id'                                    => 1,
                        'siteOpeningHours'                      => [],
                        'defaultBrakeTestClass1And2'            => null,
                        'defaultServiceBrakeTestClass3AndAbove' => null,
                        'defaultParkingBrakeTestClass3AndAbove' => null,
                        'roles'                                 => [],
                        'address' => (new AddressDto())->setAddressLine1('test')
                                                       ->setAddressLine2('test')
                                                       ->setAddressLine3('test')
                                                       ->setPostcode('test')
                                                       ->setTown('test')
                    ]
                )
            );

        return $vehicleTestingStationMapperMock;
    }

    private function getVehicleTestingStationDtoMapperMock()
    {
        $dto = (new VehicleTestingStationDto())
            ->setName(self::SITE_NAME)
            ->setId(self::SITE_ID)
            ->setContacts(
                [
                    (new SiteContactDto())->setType(SiteContactTypeCode::BUSINESS),
                ]
            );

        $this->mockVtsDtoMapper = XMock::of(VehicleTestingStationDtoMapper::class);

        $this->mockVtsDtoMapper->expects($this->any())
            ->method('getById')
            ->with(self::SITE_ID)
            ->willReturn($dto);

        $this->mockVtsDtoMapper->expects($this->any())
            ->method('getBySiteNumber')
            ->with(self::SITE_NR)
            ->willReturn($dto);

        return $this->mockVtsDtoMapper;
    }

    private function getEquipmentMapperMock()
    {
        $equipmentMapper = XMock::of(EquipmentMapper::class);

        $equipmentMapper->expects($this->any())
            ->method('fetchAllForVts')
            ->will(
                $this->returnValue([])
            );

        return $equipmentMapper;
    }

    private function getTestsInProgressMapperMock()
    {
        $equipmentMapper = XMock::of(MotTestInProgressMapper::class);

        $equipmentMapper->expects($this->any())
            ->method('fetchAllForVts')
            ->will(
                $this->returnValue([])
            );

        return $equipmentMapper;
    }

    private function getMapperFactoryMock()
    {
        $map = [
            ['VehicleTestingStation', $this->getVehicleTestingStationMapperMock()],
            ['VehicleTestingStationDto', $this->getVehicleTestingStationDtoMapperMock()],
            ['Equipment', $this->getEquipmentMapperMock()],
            ['MotTestInProgress', $this->getTestsInProgressMapperMock()],
        ];

        $mapperFactoryMock = XMock::of(MapperFactory::class);

        $mapperFactoryMock->expects($this->any())
            ->method('__get')
            ->will($this->returnValueMap($map));

        return $mapperFactoryMock;
    }
}
