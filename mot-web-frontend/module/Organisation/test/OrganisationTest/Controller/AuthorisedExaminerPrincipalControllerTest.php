<?php
namespace OrganisationTest\Controller;

use CoreTest\Controller\AbstractFrontendControllerTestCase;
use DvsaClient\Mapper\OrganisationMapper;
use DvsaClient\Mapper\PersonMapper;
use DvsaClient\MapperFactory;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Organisation\AuthorisedExaminerAuthorisationDto;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Dto\Person\PersonDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\XMock;
use Core\Service\MotFrontendAuthorisationServiceInterface;
use Organisation\Controller\AuthorisedExaminerPrincipalController;
use PHPUnit_Framework_MockObject_MockObject as MockObj;
use Zend\View\Model\ViewModel;

/**
 * Class AuthorisedExaminerPrincipalControllerTest
 */
class AuthorisedExaminerPrincipalControllerTest extends AbstractFrontendControllerTestCase
{
    const AE_ID = 1;

    /**
     * @var MotFrontendAuthorisationServiceInterface|MockObj $mockAuth
     */
    private $mockAuth;
    /**
     * @var MapperFactory|MockObj $mapper
     */
    private $mockMapperFactory;
    /**
     * @var PersonMapper|MockObj
     */
    private $mockPersonMapper;
    /**
     * @var OrganisationMapper|MockObj
     */
    private $mockOrgMapper;

    public function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);
        $this->setServiceManager($serviceManager);

        $this->mockAuth = XMock::of(MotFrontendAuthorisationServiceInterface::class);
        $this->mockMapperFactory = $this->getMapperFactory();

        $this->setController(new AuthorisedExaminerPrincipalController($this->mockAuth, $this->mockMapperFactory));

        $this->getController()->setServiceLocator($this->serviceManager);

        parent::setUp();
    }


    /**
     * @dataProvider dataProviderTestActionsResult
     */
    public function testActionsResult($method, $action, $params, $mocks, $expect)
    {
        $result = null;

        //  mocking methods
        if ($mocks !== null) {
            foreach ($mocks as $mock) {
                $invocation = (isset($mock['call']) ? $mock['call'] : $this->once());
                $mockParams = (isset($mock['params']) ? $mock['params'] : null);

                $this->mockMethod($this->{$mock['class']}, $mock['method'], $invocation, $mock['result'], $mockParams);
            }
        }

        // logical block :: call
        $result = $this->getResultForAction2(
            $method,
            $action,
            ArrayUtils::tryGet($params, 'route'),
            ArrayUtils::tryGet($params, 'get')
        );

        // logical block :: check
        if (!empty($expect['viewModel'])) {
            $this->assertEquals($expect['viewModel'], $result);
            $this->assertResponseStatus(self::HTTP_OK_CODE);
        }

        if (!empty($expect['url'])) {
            $this->assertRedirectLocation2($expect['url']);
        }
    }

    public function dataProviderTestActionsResult()
    {
        return [
            // index has access
            [
                'method' => 'get',
                'action' => 'index',
                'params' => [
                    'route' => [
                        'id' => self::AE_ID
                    ],
                ],
                'mocks' => [],
                'expect' => [
                    'viewModel' => [
                        'values' => [],
                        'cancelRoute' => AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID)
                    ],
                ],
            ],
            // index post
            [
                'method' => 'post',
                'action' => 'index',
                'params' => [
                    'route' => [
                        'id' => self::AE_ID
                    ],
                ],
                'mocks' => [
                    [
                        'class'  => 'mockPersonMapper',
                        'method' => 'createPrincipalsForOrganisation',
                        'params' => [self::AE_ID, []],
                        'result' => self::AE_ID,
                    ],
                ],
                'expect' => [
                    'url' => AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID)
                ],
            ],
            // remove Confirmation has access
            [
                'method' => 'get',
                'action' => 'removeConfirmation',
                'params' => [
                    'route' => [
                        'id' => self::AE_ID,
                        'principalId' => self::AE_ID
                    ],
                ],
                'mocks' => [],
                'expect' => [
                    'viewModel' => [
                        'principal' => $this->getPerson(),
                        'authorisedExaminer' => $this->getOrganisation(),
                        'cancelRoute' => AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID),
                    ],
                ],
            ],
            // remove Confirmation post
            [
                'method' => 'post',
                'action' => 'removeConfirmation',
                'params' => [
                    'route' => [
                        'id' => self::AE_ID,
                        'principalId' => self::AE_ID
                    ],
                ],
                'mocks' => [
                    [
                        'class'  => 'mockPersonMapper',
                        'method' => 'removePrincipalsForOrganisation',
                        'params' => [self::AE_ID, self::AE_ID],
                        'result' => self::AE_ID,
                    ],
                ],
                'expect' => [
                    'url' => AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID)
                ],
            ],
        ];
    }

    private function getMapperFactory()
    {
        $mapperFactory = XMock::of(MapperFactory::class);

        $this->mockPersonMapper = XMock::of(PersonMapper::class);
        $this->mockOrgMapper = XMock::of(OrganisationMapper::class);

        $this->mockOrgMapper->expects($this->any())
            ->method('getAuthorisedExaminer')
            ->with(self::AE_ID)
            ->willReturn($this->getOrganisation());

        $this->mockPersonMapper->expects($this->any())
            ->method('getById')
            ->with(self::AE_ID)
            ->willReturn($this->getPerson());

        $map = [
            [MapperFactory::PERSON, $this->mockPersonMapper],
            [MapperFactory::ORGANISATION, $this->mockOrgMapper],
        ];

        $mapperFactory->expects($this->any())
            ->method('__get')
            ->will($this->returnValueMap($map));

        return $mapperFactory;
    }

    private function getOrganisation()
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

        $orgDto->setContacts([$orgContactDto]);
        $orgDto->setAuthorisedExaminerAuthorisation(new AuthorisedExaminerAuthorisationDto());

        return $orgDto;
    }

    private function getPerson()
    {
        $dto = new PersonDto();
        $dto->setId(self::AE_ID);

        return $dto;
    }
}
