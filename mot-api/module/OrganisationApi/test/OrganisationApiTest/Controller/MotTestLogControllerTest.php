<?php

namespace OrganisationApiTest\Controller;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Dto\Organisation\MotTestLogSummaryDto;
use DvsaCommon\Dto\Search\MotTestSearchParamsDto;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonApiTest\Controller\AbstractRestfulControllerTestCase;
use DvsaCommonTest\TestUtils\XMock;
use DvsaElasticSearch\Service\ElasticSearchService;
use OrganisationApi\Controller\MotTestLogController;
use OrganisationApi\Service\MotTestLogService;

/**
 * Class MotTestLogControllerTest
 *
 * @package OrganisationApiTest\Controller
 */
class MotTestLogControllerTest extends AbstractRestfulControllerTestCase
{
    const ORGANISATION_ID = 1;

    /** @var MotTestLogService */
    private $motTestLogService;
    /** @var ElasticSearchService */
    private $elasticSearchService;
    /** @var EntityManager */
    private $entityManager;

    protected function setUp()
    {
        $this->motTestLogService = XMock::of(MotTestLogService::class);
        $this->elasticSearchService = XMock::of(ElasticSearchService::class);
        $this->entityManager = XMock::of(EntityManager::class);

        $controller = new MotTestLogController(
            $this->motTestLogService,
            $this->elasticSearchService,
            $this->entityManager
        );
        $this->setController($controller);

        parent::setUp();

    }



    /**
     * @dataProvider dataProviderTestActionsResultAndAccess
     */
    public function testActionsResultAndAccess($method, $action, $params, $mocks, $expect)
    {
        $result = null;

        if ($mocks !== null) {
            foreach ($mocks as $mock) {
                $this->mockMethod(
                    $this->{$mock['class']},
                    $mock['method'],
                    isset($mock['call']) ? $mock['call'] : $this->once(),
                    $mock['result'],
                    $mock['params']
                );
            }
        }

        //  --  set expected exception  --
        if (!empty($expect['exception'])) {
            $exception = $expect['exception'];
            $this->setExpectedException($exception['class'], $exception['message']);
        }

        $result = $this->getResultForAction(
            $method,
            $action,
            ArrayUtils::tryGet($params, 'route'),
            ArrayUtils::tryGet($params, 'get'),
            ArrayUtils::tryGet($params, 'post')
        );

        //  --  check   --
        if (!empty($expect['result'])) {
            $this->assertResponseStatusAndResult(self::HTTP_OK_CODE, $expect['result'], $result);
        }
    }


    public function dataProviderTestActionsResultAndAccess()
    {
        $searchParamsDto = new MotTestSearchParamsDto();
        return [
            //  --  summary: access action  --
            [
                'method' => 'get',
                'action' => 'summary',
                'params' => [
                    'route' => ['id' => self::ORGANISATION_ID],
                ],
                'mocks' => [
                    [
                        'class' => 'motTestLogService',
                        'method' => 'getMotTestLogSummaryForOrganisation',
                        'params' => [
                            self::ORGANISATION_ID,
                        ],
                        'result' => new MotTestLogSummaryDto(),
                    ],
                ],
                'expect' => [
                    'result' => ['data' => DtoHydrator::dtoToJson(new MotTestLogSummaryDto())],
                ],
            ],
            //  --  create: access action  --
            [
                'method' => 'post',
                'action' => null,
                'params' => [
                    'post' => DtoHydrator::dtoToJson($searchParamsDto),
                    'route' => ['id' => self::ORGANISATION_ID],
                ],
                'mocks' => [
                    [
                        'class' => 'elasticSearchService',
                        'method' => 'findTestsLog',
                        'params' => [],
                        'result' => 'Success',
                    ],
                ],
                'expect' => [
                    'result' => ['data' => 'Success'],
                ],
            ],
            //  --  create: access action  --
            [
                'method' => 'post',
                'action' => null,
                'params' => [
                    'post' => DtoHydrator::dtoToJson($searchParamsDto),
                    'route' => ['id' => -1],
                ],
                'mocks' => [],
                'expect' => [],
            ],
        ];
    }
}
