<?php

namespace OrganisationApiTest\Controller;

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

    private $service;

    public function setUp()
    {
        $this->service = XMock::of(AuthorisedExaminerService::class);
        $this->setController(new AuthorisedExaminerController($this->service));

        parent::setUp();
    }

    /**
     * @dataProvider dataProviderTestActionsResultAndAccess
     */
    public function testActionsResultAndAccess($method, $action, $params, $mocks, $expect)
    {
        $result = null;

        if ($mocks !== null) {
            $this->mockMethod(
                $this->service, $mocks['method'], $this->once(), $mocks['result'], $mocks['params']
            );
        }

        $result = $this->getResultForAction($method, $action, $params['route'], null, $params['post']);

        if (!empty($expect['result'])) {
            $this->assertResponseStatusAndResult(self::HTTP_OK_CODE, $expect['result'], $result);
        }

    }

    public function dataProviderTestActionsResultAndAccess()
    {
        $getServiceResult = new OrganisationDto();
        $getServiceResult->setId(self::AE_ID);

        $jsonOrganisationDto = DtoHydrator::dtoToJson($getServiceResult);

        $postServiceResult = ['id' => self::AE_ID];

        return [
            [
                'method' => 'get',
                'action' => null,
                'params' => [
                    'route' => ['id' => self::AE_ID],
                    'post' => null,
                ],
                'mocks' => [
                    'method' => 'get',
                    'params' => [self::AE_ID],
                    'result' => $getServiceResult,
                ],
                'expect' => [
                    'result' => [
                        'data' => $jsonOrganisationDto,
                    ]
                ]
            ],
            [
                'method' => 'get',
                'action' => 'getAuthorisedExaminerByNumber',
                'params' => [
                    'route' => ['number' => self::AE_ID],
                    'post' => null,
                ],
                'mocks' => [
                    'method' => 'getByNumber',
                    'params' => [self::AE_ID],
                    'result' => $getServiceResult,
                ],
                'expect' => [
                    'result' => [
                        'data' => $jsonOrganisationDto,
                    ]
                ]
            ],
            [
                'method' => 'put',
                'action' => null,
                'params' => [
                    'route' => [
                        'id' => self::AE_ID,
                        $jsonOrganisationDto
                    ],
                    'post' => null,
                ],
                'mocks' => [
                    'method' => 'update',
                    'params' => [self::AE_ID, $getServiceResult],
                    'result' => ['id' => self::AE_ID],
                ],
                'expect' => [
                    'result' => [
                        'data' => $postServiceResult,
                    ]
                ]
            ],
            [
                'method' => 'post',
                'action' => null,
                'params' => [
                    'route' => null,
                    'post' => [
                        'id' => self::AE_ID,
                        '_class' => OrganisationDto::class,
                    ],
                ],
                'mocks' => [
                    'method' => 'create',
                    'params' => $getServiceResult,
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
