<?php

namespace OrganisationApiTest\Controller;

use DvsaCommon\Constants\Role;
use DvsaCommon\Dto\Organisation\AuthorisedExaminerListItemDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonApiTest\Controller\AbstractRestfulControllerTestCase;
use DvsaCommonTest\TestUtils\XMock;
use OrganisationApi\Controller\AuthorisedExaminerController;
use OrganisationApi\Service\AuthorisedExaminerService;
use Zend\Stdlib\Parameters;

/**
 * Tests for add, edit and view AuthorisedExaminer
 *
 * @package OrganisationApiTest\Controller
 */
class AuthorisedExaminerControllerTest extends AbstractRestfulControllerTestCase
{
    const AE_ID = 9876;

    public function setUp()
    {
        $this->setController(new AuthorisedExaminerController());

        parent::setUp();
    }

    /**
     * Test method is accessible for call with valid parameters
     *
     * @param string $method        HTTP method
     * @param string $action        route action
     * @param string $serviceMethod mocked service method
     * @param array  $serviceReturn service method will return
     * @param array  $params        route parameters
     * @param array  $expectResult  expected method result
     *
     * @dataProvider dataProviderTestWithValidParam
     */
    public function testWithValidParam($method, $action, $serviceMethod, $serviceReturn, $params, $expectResult)
    {
        $this->mockValidAuthorization([Role::VEHICLE_EXAMINER]);

        $mockAEService = $this->getMockService();
        $this->setupMockForCalls($mockAEService, $serviceMethod, $serviceReturn);

        $result = $this->getResultForAction($method, $action, $params);

        $this->assertResponseStatusAndResult(self::HTTP_OK_CODE, $expectResult, $result);
    }

    public function dataProviderTestWithValidParam()
    {
        $getServiceResult = new OrganisationDto();
        $getServiceResult->setId(self::AE_ID);

        $jsonOrganisationDto = DtoHydrator::dtoToJson($getServiceResult);

        $getExpectResult = $this->getTestResponse(DtoHydrator::dtoToJson($getServiceResult));

        $postServiceResult = ['id' => self::AE_ID];
        $postExpectResult  = $this->getTestResponse($postServiceResult);

        return [
            [
                'method'        => 'get',
                'action'        => null,
                'serviceMethod' => 'get',
                'serviceReturn' => $getServiceResult,
                'params'        => ['id' => self::AE_ID],
                'expectResult'  => $getExpectResult,
            ],
            [
                'method'        => 'put',
                'action'        => null,
                'serviceMethod' => 'update',
                'serviceReturn' => $postServiceResult,
                'params'        => ['id' => self::AE_ID, 'data' => $jsonOrganisationDto],
                'expectResult'  => $postExpectResult,
            ],
            ['post', null, 'create', $postServiceResult, ['data' => []], $postExpectResult],
        ];
    }

    public function testGetAuthorisedExaminerService()
    {
        $this->assertEquals(
            $this->getMockService(),
            XMock::invokeMethod($this->getController(), 'getAuthorisedExaminerService')
        );
    }

    private function getMockService()
    {
        return $this->getMockServiceManagerClass(
            AuthorisedExaminerService::class,
            AuthorisedExaminerService::class
        );
    }

    protected function getTestResponse($data = [])
    {
        return [
            'data' => $data,
        ];
    }

    public function testGetAeByNumberAction()
    {
        $this->mockValidAuthorization([Role::VEHICLE_EXAMINER]);

        $serviceReturn = [
            'data'   => [
                (new AuthorisedExaminerListItemDto())->setId(self::AE_ID),
            ],
        ];

        $mockAEService = XMock::of(AuthorisedExaminerService::class, ['getByNumber']);
        $this->serviceManager->setService(AuthorisedExaminerService::class, $mockAEService);

        $mockAEService->expects($this->once())
            ->method('getByNumber')
            ->willReturn($serviceReturn);

        $result = $this->getResultForAction('get', 'getAuthorisedExaminerByNumber', ['number' => 'A-12345']);

        $expectedSitesData         = $serviceReturn;
        $expectedSitesData['data'] = (new DtoHydrator())->extract($expectedSitesData['data']);

        $this->assertResponseStatusAndResult(200, $this->getTestResponse($expectedSitesData), $result);
    }
}
