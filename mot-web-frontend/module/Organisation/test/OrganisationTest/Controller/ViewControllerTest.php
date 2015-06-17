<?php
namespace OrganisationTest\Controller;

use DvsaClient\Mapper\OrganisationMapper;
use DvsaClient\Mapper\OrganisationPositionMapper;
use DvsaClient\Mapper\OrganisationSitesMapper;
use DvsaClient\Mapper\PersonMapper;
use DvsaClient\MapperFactory;
use DvsaCommon\Auth\NotLoggedInException;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Dto\Person\PersonDto;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommonTest\Bootstrap;
use CoreTest\Controller\AbstractFrontendControllerTestCase;
use DvsaCommonTest\Controller\StubIdentityAdapter;
use DvsaCommonTest\TestUtils\XMock;
use Organisation\Controller\ViewController;
use Zend\View\Model\ViewModel;

/**
 * Class ViewControllerTest
 *
 * @package Organisation\Test
 */
class ViewControllerTest extends AbstractFrontendControllerTestCase
{
    const AE_ID = 1;
    const PERSON_ID = 1;

    private $mapperFactory;

    public function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);
        $this->setServiceManager($serviceManager);

        $this->setController(new ViewController());
        $this->getController()->setServiceLocator($serviceManager);

        $this->mapperFactory = $this->getMapperFactory();

        $serviceManager->setService(MapperFactory::class, $this->mapperFactory);

        $this->createHttpRequestForController('View');

        parent::setUp();
    }

    /**
     * Test has user access to page or not with/out auth and permission
     *
     * @param string  $action          Request action
     * @param array   $params          Action parameters
     * @param array   $permissions     User has permissions
     *
     * @dataProvider dataProviderTestCanAccessHasRight
     */
    public function testCanAccessHasRight(
        $action,
        $params = [],
        $permissions = []
    ) {
        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asTester());
        $this->setupAuthorizationService($permissions);

        $this->assertResponseStatus(self::HTTP_OK_CODE);
    }

    public function dataProviderTestCanAccessHasRight()
    {
        return [
            [
                'action'          => 'index',
                'params'          => [],
                'permissions'     => null,
            ],
            ['index', ['id' => 1], [PermissionAtOrganisation::AUTHORISED_EXAMINER_READ]],
            ['index', ['id' => 1], [PermissionInSystem::AUTHORISED_EXAMINER_READ_FULL]],
            ['index', ['id' => 1], []],
        ];
    }

    /**
     * Test access page for specified action and parameters
     *
     * @param string  $action          Route action
     * @param array   $params          Route parameters
     * @param boolean $expectCanAccess Has access ot not
     * @param null    $expectErrMsg    Expected error message
     *
     * @dataProvider dataProviderTestCanAccessActionWithParams
     */
    public function testCanAccessActionWithParams($action, $params, $expectCanAccess, $expectErrMsg = null)
    {
        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asTester());

        $this->setupAuthorizationService([PermissionAtOrganisation::AUTHORISED_EXAMINER_READ]);

        if (!$expectCanAccess) {
            $this->setExpectedException('Exception', $expectErrMsg);
        }

        $response = $this->getResponseForAction($action, $params);

        if ($expectCanAccess) {
            $this->assertEquals(self::HTTP_OK_CODE, $response->getStatusCode());
        }
    }

    public function dataProviderTestCanAccessActionWithParams()
    {
        return [
            [
                'action'          => 'index',
                'params'          => [],
                'expectCanAccess' => false,
                'expectErrMsg'   => ViewController::ERR_MSG_INVALID_AE_ID
            ],
            ['index', ['id' => null], false, ViewController::ERR_MSG_INVALID_AE_ID],
            ['index', ['id' => 1], true],
        ];
    }

    private function getMapperFactory()
    {
        //  ----
        $mapperFactory = XMock::of(MapperFactory::class);

        $map = [
            [MapperFactory::ORGANISATION, $this->getOrganisationMapperMock()],
            [MapperFactory::PERSON, $this->getPersonMapperMock()],
            [MapperFactory::ORGANISATION_SITE, $this->getOrganisationSitesMapperMock()],
            [MapperFactory::ORGANISATION_POSITION, $this->getOrganisationPositionMapperMock()],
        ];

        $mapperFactory->expects($this->any())
            ->method('__get')
            ->will($this->returnValueMap($map));

        return $mapperFactory;
    }

    private function getOrganisationMapperMock()
    {
        $orgDto = new OrganisationDto();
        $orgDto->setId(self::AE_ID);

        $orgContactDto = new OrganisationContactDto();
        $orgContactDto->setType(OrganisationContactTypeCode::REGISTERED_COMPANY);

        $orgAddressDto = new AddressDto();
        $orgAddressDto->setAddressLine1('test')
                      ->setAddressLine2('test')
                      ->setAddressLine3('test')
                      ->setPostcode('test')
                      ->setTown('test');

        $orgContactDto->setAddress($orgAddressDto);

        $orgDto->setContacts([ $orgContactDto ]);

        $mapper = XMock::of(OrganisationMapper::class);

        $mapper->expects($this->any())
            ->method('getAuthorisedExaminer')
            ->with(self::AE_ID)
            ->will($this->returnValue($orgDto));

        return $mapper;
    }

    private function getPersonMapperMock()
    {
        $personDto = new PersonDto();
        $personDto->setId(self::PERSON_ID);

        $mapper = XMock::of(PersonMapper::class);

        $mapper->expects($this->any())
            ->method('fetchPrincipalsForOrganisation')
            ->with(self::AE_ID)
            ->will($this->returnValue([$personDto]));

        return $mapper;
    }

    private function getOrganisationSitesMapperMock()
    {
        $mapper = XMock::of(OrganisationSitesMapper::class);

        $mapper->expects($this->any())
            ->method('fetchAllForOrganisation')
            ->with(self::AE_ID)
            ->will($this->returnValue([new VehicleTestingStationDto()]));

        return $mapper;
    }

    private function getOrganisationPositionMapperMock()
    {
        $mapper = XMock::of(OrganisationPositionMapper::class);

        $mapper
            ->expects($this->any())
            ->method('fetchAllPositionsForOrganisation')
            ->willReturn([]);

        return $mapper;
    }
}
