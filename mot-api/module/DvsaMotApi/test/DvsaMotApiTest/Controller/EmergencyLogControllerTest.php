<?php
/**
 * Created by PhpStorm.
 * User: vosabristol
 * Date: 03/12/2014
 * Time: 14:57
 */

namespace DvsaMotApiTest\Controller;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Dto\MotTesting\ContingencyMotTestDto;
use DvsaCommon\Enum\SiteBusinessRoleCode;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotApi\Controller\EmergencyLogController;
use DvsaMotApi\Service\EmergencyService;
use SiteApi\Service\SiteService;
use Zend\Session\Container;

/**
 * Class EmergencyLogControllerTest
 * @package DvsaMotApiTest\Controller
 */
class EmergencyLogControllerTest extends AbstractMotApiControllerTestCase
{
    /** @var  \SiteApi\Service\SiteService */
    protected $mockSiteService;

    /** \PersonApi\Service */
    protected $mockPersonService;

    /** @var  \DvsaMotApi\Service\EmergencyService */
    protected $mockEmergencyService;

    /** @var \DvsaEntities\Entity\Person */
    protected $mockPerson;

    /** @var  MotAuthorisationServiceInterface */
    protected $mockAuthService;

    public function setUp()
    {
        $this->controller = new EmergencyLogController();
        parent::setUp();

        // site will always return as a valid site
        $this->mockSiteService = XMock::of(
            SiteService::class,
            ['getSiteData']
        );

        $this->mockSiteService->expects($this->any())
            ->method('getSiteData')
            ->willReturn(new \stdClass());

        $this->mockPersonService = XMock::of(
            '\PersonApi\Service\PersonService',
            ['getPersonByUserReference']
        );
        $this->mockPerson = XMock::of(
            '\DvsaEntities\Entity\Person',
            ['isQualifiedTester']
        );

        $this->mockPersonService->expects($this->any())
            ->method('getPersonByUserReference')
            ->willReturn($this->mockPerson);

        $this->mockEmergencyService = XMock::of(
            '\DvsaMotApi\Service\EmergencyService',
            ['getEmergencyLog']
        );

        $this->mockAuthService = XMock::of(
            '\DvsaCommon\Auth\MotAuthorisationServiceInterface',
            ['assertGranted']
        );

        $this->setQualifiedTester(true);

        $this->serviceManager->setService('PersonService', $this->mockPersonService);
        $this->serviceManager->setService(SiteService::class, $this->mockSiteService);
        $this->serviceManager->setService(EmergencyService::class, $this->mockEmergencyService);
        $this->serviceManager->setService('DvsaAuthorisationService', $this->mockAuthService);
    }

    protected function setQualifiedTester($mode)
    {
        $this->mockPerson->expects($this->any())
            ->method('isQualifiedTester')
            ->willReturn($mode);
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     */
    public function testFailsIfDtoObjectFailsToHydrate()
    {
        $this->mockValidAuthorization([SiteBusinessRoleCode::TESTER]);

        $this->request->setMethod('post');
        $this->request->setContent(json_encode([]));
        $this->request->setHeaders(\Zend\Http\Headers::fromString('Content-type: application/json'));

        /** @var $result \Zend\View\Model\JsonModel */
        $this->controller->dispatch($this->request);

        $this->assertResponseStatus(self::HTTP_ERR_400);
    }

    public function testValidHydration()
    {
        $dto = new ContingencyMotTestDto();
        $today =  DateUtils::today();

        $dto->setTestedByWhom('current');
        $dto->setSiteId(1);
        $dto->setTestType('normal');
        $dto->setPerformedAt($today->format('Y-m-d'));
        $dto->setDateYear($today->format('Y'));
        $dto->setDateMonth($today->format('m'));
        $dto->setDateDay($today->format('d'));
        $dto->setReasonCode('OT');
        $dto->setReasonText('this is some text');
        $dto->setTesterCode('Tester10');
        $dto->setContingencyCode('12345A');

        $dtoArray = DtoHydrator::dtoToJson($dto);

        $this->mockValidAuthorization([SiteBusinessRoleCode::TESTER]);
        $this->request->setMethod('post');
        $this->setJsonRequestContent($dtoArray);

        // Return the expected log ID
        eval("class DummyLog1 { public function getId() { return 3; }}");
        $this->mockEmergencyService->expects($this->once())
            ->method('getEmergencyLog')
            ->with('12345A')
            ->willReturn(new \DummyLog1());

        /** @var $result \Zend\View\Model\JsonModel */
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode(), 'Dto wrongfully did not validate');
    }

    public function testResponseDataWhenValid()
    {
        $dto = new ContingencyMotTestDto();
        $today =  DateUtils::today();

        $dto->setTestedByWhom('current');
        $dto->setSiteId(1);
        $dto->setTestType('normal');
        $dto->setPerformedAt($today->format('Y-m-d'));
        $dto->setDateYear($today->format('Y'));
        $dto->setDateMonth($today->format('m'));
        $dto->setDateDay($today->format('d'));
        $dto->setReasonCode('OT');
        $dto->setReasonText('this is some text');
        $dto->setTesterCode('Tester10');
        $dto->setContingencyCode('12345A'); // Corresponds to ID 3

        $dtoArray = DtoHydrator::dtoToJson($dto);

        $this->mockValidAuthorization([SiteBusinessRoleCode::TESTER]);
        $this->request->setMethod('post');
        $this->setJsonRequestContent($dtoArray);

        // Return the expected log ID
        eval("class DummyLog2 { public function getId() { return 3; }}");
        $this->mockEmergencyService->expects($this->once())
            ->method('getEmergencyLog')
            ->with('12345A')
            ->willReturn(new \DummyLog2());

        /** @var $result \Zend\View\Model\JsonModel */
        $result = $this->controller->dispatch($this->request);

        $this->assertResponseStatusAndResult(
            self::HTTP_OK_CODE,
            [
                'data' => ['emergencyLogId' => 3]
            ],
            $result
        );
    }
}
