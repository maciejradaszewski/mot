<?php
namespace DvsaMotTestTest\Controller;

use Dvsa\Mot\Frontend\Plugin\AjaxResponsePlugin;
use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommon\Dto\Common\MotTestTypeDto;
use DvsaCommon\Dto\Person\PersonDto;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\UrlBuilder\MotTestUrlBuilderWeb;
use DvsaCommon\UrlBuilder\UrlBuilder;
use DvsaMotTest\Controller\TestItemSelectorController;
use Zend\Http\Header\Location;
use Zend\Stdlib\Parameters;

/**
 * Class TestItemSelectorControllerTest
 */
class TestItemSelectorControllerTest extends AbstractDvsaMotTestTestCase
{
    protected function setUp()
    {
        $this->controller = new TestItemSelectorController();

        parent::setUp();
        $this->controller->getPluginManager()
            ->setInvokableClass('ajaxResponse', AjaxResponsePlugin::class);
    }

    public function testTestItemSelectorsGetData()
    {
        $motTestNumber = 1;
        $testItemSelectorId = 502;

        $this->getRestClientMock(
            'get',
            $this->getTestDataItemSelectorsDto($testItemSelectorId),
            "mot-test/$motTestNumber/test-item-selector/$testItemSelectorId"
        );

        $routeParams = [
            'motTestNumber' => $motTestNumber,
            'tis-id'        => $testItemSelectorId,
        ];

        $result = $this->getResultForAction('testItemSelectors', $routeParams);

        $this->assertResponseStatus(self::HTTP_OK_CODE);
        $variables = $result->getVariables();
        $testData = $this->getTestDataItemSelectors($testItemSelectorId);
        $testData = $testData['data']['testItemSelectors'];
        $this->assertEquals($testData, $variables['testItemSelectors']);
    }

    /**
     * If there is 1 or less test item selector entries, then no breadcrumb
     * links should be generated. This happens when at the RFR home screen.
     */
    public function testEmptyArrayForBreadcrumbLinksWhenAtRfrHome()
    {
        $motTestNumber = 1;
        $testItemSelectorId = 502;

        $this->getRestClientMock(
            'get',
            $this->getSingleResultTestItemSelectorsDataDto(),
            "mot-test/$motTestNumber/test-item-selector/$testItemSelectorId"
        );

        $routeParams = [
            'motTestNumber' => $motTestNumber,
            'tis-id'        => $testItemSelectorId,
        ];

        $result = $this->getResultForAction('testItemSelectors', $routeParams);

        $this->assertResponseStatus(self::HTTP_OK_CODE);

        $variables = $result->getVariables();
        $this->assertEquals(0, count($variables['breadcrumbItemSelectors']));
    }

    /**
     * The breadcrumb links should appear when navigating through the RFR
     * screens.
     */
    public function testBreadcrumbLinksWhenNotAtRfrHome()
    {
        $motTestNumber = 1;
        $testItemSelectorId = 502;

        $this->getRestClientMock(
            'get',
            $this->getMultipleTestItemSelectorDataDto(),
            "mot-test/$motTestNumber/test-item-selector/$testItemSelectorId"
        );

        $routeParams = [
            'motTestNumber' => $motTestNumber,
            'tis-id'        => $testItemSelectorId,
        ];

        $result = $this->getResultForAction('testItemSelectors', $routeParams);

        $this->assertResponseStatus(self::HTTP_OK_CODE);

        $variables = $result->getVariables();
        $this->assertEquals(2, count($variables['breadcrumbItemSelectors']));
    }

    public function testTestItemSelectorsWithMotTestGetData()
    {
        $motTestNumber = 1;
        $testItemSelectorId = 502;

        $this->getRestClientMock(
            'get',
            $this->getEmptyTestItemsDto(),
            "mot-test/$motTestNumber/test-item-selector/$testItemSelectorId"
        );

        $routeParams = [
            'motTestNumber' => $motTestNumber,
            'tis-id'        => $testItemSelectorId,
        ];

        $result = $this->getResultForAction('testItemSelectors', $routeParams);

        $this->assertResponseStatus(self::HTTP_OK_CODE);

        $variables = $result->getVariables();
        $testData = $this->getEmptyTestItemsDto();
        $motTestData = $testData['data']['motTest'];
        $this->assertEquals($motTestData, $variables['motTestDetails']);
    }

    public function testTestItemSelectorsShowInfoAboutMissing()
    {
        $testItemSelectorId = 502;

        $this->getRestClientMock('get', $this->getEmptyTestItemsDto($testItemSelectorId));
        $this->getFlashMessengerMockForAddInfoMessage(TestItemSelectorController::NO_RFRS_FOUND_INFO_MESSAGE);

        $this->routeMatch->setParam('action', 'testItemSelectors');
        $this->routeMatch->setParam('id', '1');
        $this->routeMatch->setParam('tis-id', $testItemSelectorId);

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertResponseStatus(self::HTTP_OK_CODE);

        $variables = $result->getVariables();
        $this->assertEmpty($variables['testItemSelectors']);
    }

    public function testTestItemSelectorsRestException()
    {
        $testItemSelectorId = 502;

        $this->getRestClientMockThrowingException('get');
        $this->getFlashMessengerMockForAddErrorMessage('REST ERROR!');

        $this->routeMatch->setParam('action', 'testItemSelectors');
        $this->routeMatch->setParam('id', '1');
        $this->routeMatch->setParam('tis-id', $testItemSelectorId);

        $result = $this->controller->dispatch($this->request);

        $this->assertResponseStatus(self::HTTP_OK_CODE);

        $variables = $result->getVariables();
        $this->assertEmpty($variables['testItemSelectors']);
    }

    public function testSearchGetData()
    {
        $motTestNumber = 1;
        $searchString = "stop lamp";
        $start = "0";
        $end = "10";
        $hasMore = true;
        $rfrs = [['id' => 1], ['id' => 2]];
        $restData = [
            'data' => [
                'searchDetails'       => ['hasMore' => $hasMore],
                'reasonsForRejection' => $rfrs,
                'motTest'             => $this->getMotTest()->setMotTestNumber(1),
            ]
        ];

        $this->getRestClientMock(
            'get',
            $restData,
            "mot-test/$motTestNumber/reason-for-rejection?search=$searchString&start=$start&end=$end"
        );

        $this->routeMatch->setParam('action', 'search');
        $this->routeMatch->setParam('motTestNumber', $motTestNumber);
        $this->request->setQuery(
            new Parameters(
                [
                    'search' => $searchString,
                    'start'  => $start,
                    'end'    => $end
                ]
            )
        );

        $result = $this->controller->dispatch($this->request);

        $this->assertResponseStatus(self::HTTP_OK_CODE);

        $variables = $result->getVariables();
        $this->assertEquals($rfrs, $variables['reasonsForRejection']);
        $this->assertEquals($motTestNumber, $variables['motTestNumber']);
        $this->assertEquals($searchString, $variables['searchString']);
        $this->assertEquals($start, $variables['start']);
        $this->assertEquals($hasMore, $variables['hasMore']);
    }

    public function testSearchWithNoResultsDisplaysError()
    {
        $motTestNumber = 1;
        $searchString = "No results will be found for this";
        $restData = [
            'data' => [
                'searchDetails'        => ['hasMore' => false],
                'reasonsForRejection' => [],
                'motTest'              => $this->getMotTest()->setMotTestNumber(1),
            ]
        ];

        $this->getRestClientMock(
            'get',
            $restData
        );
        $this->getFlashMessengerMockForAddErrorMessage(
            TestItemSelectorController::NO_SEARCH_RESULTS_FOUND_ERROR_MESSAGE
        );

        $this->routeMatch->setParam('action', 'search');
        $this->routeMatch->setParam('motTestNumber', $motTestNumber);
        $this->request->setQuery(new Parameters(['search' => $searchString]));

        $this->controller->dispatch($this->request);

        $this->assertResponseStatus(self::HTTP_OK_CODE);
    }

    public function testSearchDisplaysRestException()
    {
        $motTestNumber = 1;
        $searchString = "something about this search is wrong";

        $this->getRestClientMockThrowingException('get');
        $this->getFlashMessengerMockForAddErrorMessage('REST ERROR!');

        $this->routeMatch->setParam('action', 'search');
        $this->routeMatch->setParam('motTestNumber', $motTestNumber);
        $this->request->setQuery(new Parameters(['search' => $searchString]));

        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testSearchWithNoSearchStringReturnsErrorMessage()
    {
        $motTestNumber = 1;

        $this->getFlashMessengerMockForAddErrorMessage(TestItemSelectorController::NO_SEARCH_STRING_ERROR_MESSAGE);

        $this->routeMatch->setParam('action', 'search');
        $this->routeMatch->setParam('motTestNumber', $motTestNumber);

        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testTestReasonsForRejectionGetData()
    {
        $testItemSelectorId = 502;
        $motTestNumber = 1;

        $this->getRestClientMock(
            'get',
            $this->getTestDataItemRfrsDto($testItemSelectorId),
            "mot-test/$motTestNumber/test-item-selector/$testItemSelectorId"
        );

        $this->routeMatch->setParam('action', 'testItemSelectors');
        $this->routeMatch->setParam('motTestNumber', $motTestNumber);
        $this->routeMatch->setParam('tis-id', $testItemSelectorId);

        $result = $this->controller->dispatch($this->request);

        $this->assertResponseStatus(self::HTTP_OK_CODE);

        $variables = $result->getVariables();
        $testData = $this->getTestDataItemRfrs($testItemSelectorId);
        $testData = $testData['data']['reasonsForRejection'];
        $this->assertEquals($testData, $variables['reasonsForRejection']);
    }

    public function testTestItemSelectorRfrsShowInfoAboutMissing()
    {
        $testItemSelectorId = 502;

        $this->getRestClientMock('get', $this->getEmptyTestItemsDto($testItemSelectorId));
        $this->getFlashMessengerMockForAddInfoMessage(TestItemSelectorController::NO_RFRS_FOUND_INFO_MESSAGE);

        $this->routeMatch->setParam('action', 'testItemSelectors');
        $this->routeMatch->setParam('id', '1');
        $this->routeMatch->setParam('tis-id', $testItemSelectorId);
        $this->request->setMethod('post');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertResponseStatus(self::HTTP_OK_CODE);

        $variables = $result->getVariables();
        $this->assertEmpty($variables['reasonsForRejection']);
    }

    public function testAddReasonForRejectionWithValidData()
    {
        $motTestNumber = 1;
        $testItemSelectorId = 502;
        $rfrId = 2;
        $type = 'FAIL';
        $locationLateral = 'nearside';
        $locationLongitudinal = 'front';
        $locationVertical = 'top';
        $comment = "Test comment";
        $failureDangerous = true;

        $expectedRestPostData = [
            'rfrId'                => $rfrId,
            'type'                 => $type,
            'locationLateral'      => $locationLateral,
            'locationLongitudinal' => $locationLongitudinal,
            'locationVertical'     => $locationVertical,
            'comment'              => $comment,
            'failureDangerous'     => $failureDangerous,
        ];

        $this->routeMatch->setParam('action', 'addReasonForRejection');
        $this->routeMatch->setParam('motTestNumber', $motTestNumber);
        $this->routeMatch->setParam('tis-id', $testItemSelectorId);
        $this->routeMatch->setParam('rfr-id', $rfrId);
        $this->request->setMethod('post');
        $this->request->getPost()->set('locationLateral', $locationLateral);
        $this->request->getPost()->set('locationLongitudinal', $locationLongitudinal);
        $this->request->getPost()->set('locationVertical', $locationVertical);
        $this->request->getPost()->set('comment', $comment);
        $this->request->getPost()->set('failureDangerous', $failureDangerous);
        $this->request->getPost()->set('type', $type);

        $restClientMock = $this->getRestClientMockForServiceManager();
        $restClientMock->expects($this->once())
            ->method('postJson')
            ->with("mot-test/$motTestNumber/reasons-for-rejection", $expectedRestPostData);

        /**
         * @var JsonModel $result
         */
        $result = $this->controller->dispatch($this->request);

        $this->assertResponseStatus(self::HTTP_OK_CODE);

        $redirectUrl = $result->getVariables()['redirectUrl'];
        $this->assertEquals(
            $redirectUrl,
            "/mot-test/$motTestNumber/test-item-selector/$testItemSelectorId"
        );
    }

    public function testAddReasonForRejectionWithValidDataRedirectsToSearchWhenSearchStringSet()
    {
        $motTestNumber = 1;
        $testItemSelectorId = 502;
        $rfrId = 2;
        $type = 'FAIL';
        $searchString = "Stop lamp";
        $searchStringUri = "Stop%20lamp";

        $this->routeMatch->setParam('action', 'addReasonForRejection');
        $this->routeMatch->setParam('motTestNumber', $motTestNumber);
        $this->routeMatch->setParam('tis-id', $testItemSelectorId);
        $this->routeMatch->setParam('rfr-id', $rfrId);
        $this->request->setMethod('post');
        $this->request->getPost()->set('type', $type);
        $this->request->getPost()->set('searchString', $searchString);

        $restClientMock = $this->getRestClientMockForServiceManager();
        $restClientMock->expects($this->once())
            ->method('postJson');

        $result = $this->controller->dispatch($this->request);

        $this->assertResponseStatus(self::HTTP_OK_CODE);

        $redirectUrl = $result->getVariables()['redirectUrl'];
        $this->assertEquals(
            "/mot-test/$motTestNumber/test-item-selector-search?search=$searchStringUri", $redirectUrl
        );
    }

    public function testAddReasonForRejectionWithInvalidData()
    {
        $motTestNumber = 1;
        $testItemSelectorId = 502;
        $rfrId = 2;
        $errorMessage = "Bad RFR";

        $this->routeMatch->setParam('action', 'addReasonForRejection');
        $this->routeMatch->setParam('motTestNumber', $motTestNumber);
        $this->routeMatch->setParam('tis-id', $testItemSelectorId);
        $this->routeMatch->setParam('rfr-id', $rfrId);
        $this->request->setMethod('post');

        $this->getRestClientMockThrowingException('postJson', $errorMessage);

        $result = $this->controller->dispatch($this->request);

        $this->assertResponseStatus(self::HTTP_OK_CODE);

        $jsonMessage = $result->getVariables()['data']['messages'][0];
        $this->assertEquals($jsonMessage, $errorMessage);
    }

    public function test_testItemSelectorsAction_navigates_back_if_test_is_not_active()
    {
        $testItemSelectorId = 502;
        $motTestNumber = 1;

        $this->getRestClientMock(
            'get',
            $this->getTestDataItemRfrsDto($testItemSelectorId, 'FAILED'),
            "mot-test/$motTestNumber/test-item-selector/$testItemSelectorId"
        );

        $routeParams = [
            'motTestNumber' => $motTestNumber,
            'tis-id'        => $testItemSelectorId,
        ];

        $this->getResultForAction('testItemSelectors', $routeParams);

        $this->assertRedirectLocation2(MotTestUrlBuilderWeb::motTest($motTestNumber));
    }

    public function test_suggestionsAction_transfers_data_from_api()
    {
        $motTestNumber = 4;
        $apiReturnData = [ 'data' => ['xxx' => 'yyy'] ];

        $this->routeMatch->setParam('action', 'suggestions');
        $this->routeMatch->setParam('motTestNumber', $motTestNumber);

        $route = UrlBuilder::of()->testItemCategoryName()->routeParam('motTestNumber', $motTestNumber)->toString();
        $this->getRestClientMock('get', $apiReturnData, $route);

        $result = $this->controller->dispatch($this->request);

        $this->assertResponseStatus(self::HTTP_OK_CODE);

        $controllerReturnData = $result->getVariables();

        $this->assertEquals($apiReturnData, $controllerReturnData);
    }

    //TODO: test editReasonForRejection

    protected function getTestDataItemSelectors($id)
    {
        return ["data" => [
            "testItemSelector"        => [
                "id"                        => 0,
                "name"                      => "Vehicle",
                "description"               => "Vehicle",
                "vehicleClass"              => "4",
                "sectionTestItemSelectorId" => 0,
                "parentTestItemSelectorId"  => 0
            ],
            "parentTestItemSelectors" => [],
            "testItemSelectors"       => [
                [
                    "id"                        => $id,
                    "name"                      => "Towbar",
                    "description"               => "Towbar",
                    "vehicleClass"              => "4",
                    "sectionTestItemSelectorId" => 501,
                    "parentTestItemSelectorId"  => 501
                ],
                [
                    "id"                        => $id,
                    "name"                      => "Bowbar",
                    "description"               => "Bowbar",
                    "vehicleClass"              => "4",
                    "sectionTestItemSelectorId" => 660,
                    "parentTestItemSelectorId"  => 666
                ],
            ],
            "reasonsForRejection" => [],
            "motTest"                 => [
                'status' => 'ACTIVE',
                'tester' => ['userId' => 1]
            ]
        ],
        ];
    }

    private function getTestDataItemSelectorsDto($id)
    {
        return ["data" => [
            "testItemSelector"        => [
                "id"                        => 0,
                "name"                      => "Vehicle",
                "description"               => "Vehicle",
                "vehicleClass"              => "4",
                "sectionTestItemSelectorId" => 0,
                "parentTestItemSelectorId"  => 0
            ],
            "parentTestItemSelectors" => [],
            "testItemSelectors"       => [
                [
                    "id"                        => $id,
                    "name"                      => "Towbar",
                    "description"               => "Towbar",
                    "vehicleClass"              => "4",
                    "sectionTestItemSelectorId" => 501,
                    "parentTestItemSelectorId"  => 501
                ],
                [
                    "id"                        => $id,
                    "name"                      => "Bowbar",
                    "description"               => "Bowbar",
                    "vehicleClass"              => "4",
                    "sectionTestItemSelectorId" => 660,
                    "parentTestItemSelectorId"  => 666
                ],
            ],
            "reasonsForRejection" => [],
            "motTest"                 => $this->getMotTest(MotTestStatusName::ACTIVE, 1),
        ],
        ];
    }

    protected function getTestDataItemRfrsDto($id, $status = 'ACTIVE', $testerId = 1)
    {
        return [
            "data" => [
                "testItemSelector"        => [
                    "id"                        => 0,
                    "name"                      => "Vehicle",
                    "description"               => "Vehicle",
                    "vehicleClass"              => "4",
                    "sectionTestItemSelectorId" => 0,
                    "parentTestItemSelectorId"  => 0
                ],
                "testItemSelectors"       => [],
                "parentTestItemSelectors" => [],
                "reasonsForRejection" => [
                    [
                        "rfrId"                       => $id,
                        "inspectionManualReference"   => "1.7.2",
                        "minorItem"                   => false,
                        "description"                 => "inoperative",
                        "locationMarker"              => false,
                        "qtMarker"                    => true,
                        "note"                        => true,
                        "manual"                      => "4",
                        "specProc"                    => false,
                        "inspectionManualDescription" =>
                            "A headlamp levelling or cleaning device inoperative or otherwise obviously defective",
                        "advisoryText"                => "",
                        "vehicleClass"                => "4",
                        "testItemSelector"            => "507",
                        "sectionTestItemSelector"     => "5000"
                    ],
                ],
                "motTest"                 => $this->getMotTest($status, $testerId),
            ]
        ];
    }

    protected function getTestDataItemRfrs($id, $status = 'ACTIVE', $testerId = 1)
    {
        return [
            "data" => [
                "testItemSelector"        => [
                    "id"                        => 0,
                    "name"                      => "Vehicle",
                    "description"               => "Vehicle",
                    "vehicleClass"              => "4",
                    "sectionTestItemSelectorId" => 0,
                    "parentTestItemSelectorId"  => 0
                ],
                "testItemSelectors"       => [],
                "parentTestItemSelectors" => [],
                "reasonsForRejection" => [
                    [
                        "rfrId"                       => $id,
                        "inspectionManualReference"   => "1.7.2",
                        "minorItem"                   => false,
                        "description"                 => "inoperative",
                        "locationMarker"              => false,
                        "qtMarker"                    => true,
                        "note"                        => true,
                        "manual"                      => "4",
                        "specProc"                    => false,
                        "inspectionManualDescription" =>
                            "A headlamp levelling or cleaning device inoperative or otherwise obviously defective",
                        "advisoryText"                => "",
                        "vehicleClass"                => "4",
                        "testItemSelector"            => "507",
                        "sectionTestItemSelector"     => "5000"
                    ],
                ],
                "motTest"                 => [
                    'status' => $status,
                    'tester' => ['userId' => $testerId],
                ]
            ]
        ];
    }

    /**
     * Simulate the test_item_selectors data returned from the API that will
     * be used on the RFT home page
     *
     * @return array
     */
    protected function getSingleResultTestItemSelectorsData()
    {
        $items = $this->getEmptyTestItems();
        $items['data']['testItemSelector'] = [
            [
                'name' => 'RFR Home',
                'nameCy' => '' ,
                'description' => '',
                'sectionTestItemSelectorId' => 0,
                'parentTestItemSelectorId' => 0,
                'id' => 0,
                'vehicleClasses' => []
            ]
        ];

        return $items;
    }

    private function getSingleResultTestItemSelectorsDataDto()
    {
        $items = $this->getEmptyTestItemsDto();
        $items['data']['testItemSelector'] = [
            [
                'name' => 'RFR Home',
                'nameCy' => '' ,
                'description' => '',
                'sectionTestItemSelectorId' => 0,
                'parentTestItemSelectorId' => 0,
                'id' => 0,
                'vehicleClasses' => []
            ]
        ];

        return $items;
    }

    /**
     * Simulate the response data for test_item_selectors when user has
     * navigated deeper than the RFR home screen.
     *
     * @return array
     */
    protected function getMultipleTestItemSelectorData()
    {
        $items = $this->getSingleResultTestItemSelectorsData();
        // make it look like we are 2 levels deep
        $items['data']['parentTestItemSelectors'][] = $items['data']['testItemSelector'];
        return $items;
    }

    private function getMultipleTestItemSelectorDataDto()
    {
        $items = $this->getSingleResultTestItemSelectorsDataDto();
        // make it look like we are 2 levels deep
        $items['data']['parentTestItemSelectors'][] = $items['data']['testItemSelector'];
        return $items;
    }

    protected function getEmptyTestItems()
    {
        return ["data" => [
            "testItemSelector"        => [],
            "testItemSelectors"       => [],
            "parentTestItemSelectors" => [],
            "reasonsForRejection" => [],
            "motTest"                 => [
                "status" => 'ACTIVE',
                'tester' => ['userId' => 1]
            ],
        ]];
    }

    private function getEmptyTestItemsDto()
    {
        return ["data" => [
            "testItemSelector"        => [],
            "testItemSelectors"       => [],
            "parentTestItemSelectors" => [],
            "reasonsForRejection"     => [],
            "motTest"                 => $this->getMotTest(),
        ]];
    }

    /**
     * @return MotTestDto
     */
    private function getMotTest($status = null, $testerId = null)
    {
        return (new MotTestDto())
            ->setStatus($status ?: 'ACTIVE')
            ->setTester(
                (new PersonDto())->setId($testerId ?: 1)
            )
            ->setTestType(
                (new MotTestTypeDto())->setCode(MotTestTypeCode::NORMAL_TEST)
            );
    }
}
