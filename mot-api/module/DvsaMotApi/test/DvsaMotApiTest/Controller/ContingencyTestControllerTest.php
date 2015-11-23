<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaMotApiTest\Controller;

use DateTime;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Dto\MotTesting\ContingencyTestDto;
use DvsaCommon\Enum\SiteBusinessRoleCode;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommon\Validation\ValidationResult;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotApi\Controller\ContingencyTestController;
use DvsaMotApi\Service\EmergencyService;
use DvsaMotApi\Validation\ContingencyTestValidator;
use SiteApi\Service\SiteService;
use Zend\Http\Headers;

/**
 * Class ContingencyTestControllerTest.
 */
class ContingencyTestControllerTest extends AbstractMotApiControllerTestCase
{
    /**
     * @var ContingencyTestValidator
     */
    private $contingencyTestValidator;

    /**
     * @var \SiteApi\Service\SiteService
     */
    protected $mockSiteService;

    /**
     * \PersonApi\Service */
    protected $mockPersonService;

    /**
     * @var \DvsaMotApi\Service\EmergencyService
     */
    protected $mockEmergencyService;

    /**
     * @var \DvsaEntities\Entity\Person
     */
    protected $mockPerson;

    /**
     * @var MotAuthorisationServiceInterface
     */
    protected $mockAuthService;

    public function setUp()
    {
        $this->contingencyTestValidator = $this->createContingencyTestValidator(true);
        $this->controller = new ContingencyTestController($this->contingencyTestValidator);
        parent::setUp();

        // site will always return as a valid site
        $this->mockSiteService = XMock::of(
            SiteService::class,
            ['getSite']
        );

        $this->mockSiteService->expects($this->any())
            ->method('getSite')
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
        $this->markTestSkipped();

        $this->mockValidAuthorization([SiteBusinessRoleCode::TESTER]);

        $this->request->setMethod('post');
        $this->request->setContent(json_encode([]));
        $this->request->setHeaders(Headers::fromString('Content-type: application/json'));

        /* @var $result \Zend\View\Model\JsonModel */
        $this->controller->dispatch($this->request);

        $this->assertResponseStatus(self::HTTP_ERR_400);
    }

    public function testValidHydration()
    {
        $this->markTestSkipped();

        $dto = new ContingencyTestDto();

        $dto->setSiteId(1);
        $dto->setPerformedAt(new DateTime());
        $dto->setReasonCode('OT');
        $dto->setOtherReasonText('this is some text');
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
        $this->markTestSkipped();

        $dto = new ContingencyTestDto();

        $dto->setSiteId(1);
        $dto->setPerformedAt(new DateTime());
        $dto->setReasonCode('OT');
        $dto->setOtherReasonText('this is some text');
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
                'data' => ['emergencyLogId' => 3],
            ],
            $result
        );
    }

    /**
     * @param bool $shouldValidate
     *
     * @return ContingencyTestValidator
     */
    private function createContingencyTestValidator($shouldValidate)
    {
        $contingencyTestValidator = $this
            ->getMockBuilder(ContingencyTestValidator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $validationResult = new ValidationResult($shouldValidate);

        $contingencyTestValidator
            ->method('validate')
            ->willReturn($validationResult);

        return $contingencyTestValidator;
    }
}
