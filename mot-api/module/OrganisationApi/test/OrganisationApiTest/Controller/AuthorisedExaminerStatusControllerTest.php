<?php

namespace OrganisationApiTest\Controller;

use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonApiTest\Controller\AbstractRestfulControllerTestCase;
use DvsaCommonTest\TestUtils\XMock;
use OrganisationApi\Controller\AuthorisedExaminerStatusController;
use OrganisationApi\Service\AuthorisedExaminerStatusService;
use PHPUnit_Framework_MockObject_MockObject as MockObj;
use Zend\Stdlib\Parameters;

class AuthorisedExaminerStatusControllerTest extends AbstractRestfulControllerTestCase
{
    const AE_ID = 9876;

    /**
     * @var AuthorisedExaminerStatusService|MockObj
     */
    private $service;

    public function setUp()
    {
        $this->service = XMock::of(AuthorisedExaminerStatusService::class);
        $this->setController(new AuthorisedExaminerStatusController($this->service));

        parent::setUp();
    }

    /**
     * @dataProvider dataProviderTestActionsResultAndAccess
     */
    public function testActionsResultAndAccess($method, $action, $params, $mocks, $expect)
    {
        //  mock methods of classes
        if ($mocks !== null) {
            $this->mockMethod(
                $this->service, $mocks['method'], $this->once(), $mocks['result'], $mocks['params']
            );
        }

        //  call
        $result = $this->getResultForAction($method, $action, $params['route'], null, $params['post']);

        //  check
        $this->assertResponseStatusAndResult(self::HTTP_OK_CODE, $expect['result'], $result);
    }

    public function dataProviderTestActionsResultAndAccess()
    {
        $getServiceResult = new OrganisationDto();
        $getServiceResult->setId(self::AE_ID);

        $jsonOrganisationDto = DtoHydrator::dtoToJson($getServiceResult);

        $postServiceResult = ['id' => self::AE_ID];

        return [
            [
                'method' => 'put',
                'action' => null,
                'params' => [
                    'route' => [
                        'id' => self::AE_ID,
                        $jsonOrganisationDto
                    ],
                    'post'  => null,
                ],
                'mocks'  => [
                    'method' => 'updateStatus',
                    'params' => [self::AE_ID, $getServiceResult],
                    'result' => ['id' => self::AE_ID],
                ],
                'expect' => [
                    'result' => [
                        'data' => $postServiceResult,
                    ]
                ]
            ],
        ];
    }
}
