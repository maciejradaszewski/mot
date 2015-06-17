<?php
namespace DvsaMotTest\Controller;

use Application\Service\ContingencySessionManager;
use Core\Authorisation\Assertion\WebPerformMotTestAssertion;
use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\Messages\InvalidTestStatus;
use DvsaCommon\UrlBuilder\UrlBuilder;
use DvsaCommon\Utility\ArrayUtils;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Class TestItemSelectorController
 */
class TestItemSelectorController extends AbstractDvsaMotTestController
{
    const NO_RFRS_FOUND_INFO_MESSAGE = 'No Reasons for Rejection under this category';
    const NO_SEARCH_STRING_ERROR_MESSAGE = "You must enter search criteria";
    const NO_SEARCH_RESULTS_FOUND_ERROR_MESSAGE = "No items found please refine search";

    // TODO switch to UrlBuilder
    const MOT_TEST_RFR_API_PATH_FORMAT = 'mot-test/%d/reasons-for-rejection';
    // TODO switch to UrlBuilder
    const TEST_ITEM_SELECTOR_API_PATH_FORMAT = 'mot-test/%d/test-item-selector/%s';
    // TODO switch to UrlBuilder
    const TEST_ITEM_SELECTOR_RFR_SEARCH_API_PATH_FORMAT
        = 'mot-test/%d/reason-for-rejection?search=%s&start=%s&end=%s';

    const QUERY_PARAM_SEARCH = 'search';
    const QUERY_PARAM_SEARCH_START = 'start';
    const QUERY_PARAM_SEARCH_END = 'end';

    const TEMPLATE_CATEGORIES = 'dvsa-mot-test/test-item-selector/test-item-selectors';
    const TEMPLATE_RFRS = 'dvsa-mot-test/test-item-selector/reasons-for-rejection';

    private $motTestNumber;
    private $testItemSelectorId;
    private $rfrId;

    protected function initializeRouteParams()
    {
        $this->motTestNumber = (int)$this->params()->fromRoute('motTestNumber', null);
        $this->testItemSelectorId = (int)$this->params()->fromRoute('tis-id', null);
    }

    protected function initializeRouteParamsWithRfrId()
    {
        $this->initializeRouteParams();
        $this->rfrId = (int)$this->params()->fromRoute('rfr-id', null);
    }

    public function testItemSelectorsAction()
    {
        $this->initializeRouteParams();

        $resultData = null;
        $showCategories = true;
        try {
            $resultData = $this->getDataFromApi($this->getTestItemSelectorApiPath());
            $this->getPerformMotTestAssertion()->assertGranted($resultData['motTest']);

            /** @var MotTestDto $motTest */
            $motTest = $resultData['motTest'];

            if ($motTest->getStatus() !== MotTestStatusName::ACTIVE) {
                $this->addErrorMessages([InvalidTestStatus::getMessage($motTest->getStatus())]);
                return $this->redirect()->toRoute('mot-test', ['motTestNumber' => $this->motTestNumber]);
            }

            if (!count($this->getTestItemSelectors($resultData))) {
                if (!count($this->getReasonsForRejection($resultData))) {
                    $this->addInfoMessages(self::NO_RFRS_FOUND_INFO_MESSAGE);
                } else {
                    $showCategories = false;
                }
            }
        } catch (RestApplicationException $e) {
            $this->addErrorMessages($e->getDisplayMessages());
        }
        $viewParams = [
            'isMotContingency'        => $this->getContingencySessionManager()->isMotContingency(),
            'breadcrumbItemSelectors' => $this->getBreadcrumbTestItemSelectors($resultData),
            'testItemSelectors'       => $this->getTestItemSelectors($resultData),
            'reasonsForRejection'     => $this->getReasonsForRejection($resultData),
            'motTestDetails'          => $this->getMotTest($resultData),
        ];
        $viewModel = new ViewModel($viewParams);
        $viewModel->setTemplate($showCategories ? self::TEMPLATE_CATEGORIES : self::TEMPLATE_RFRS);
        return $viewModel;
    }

    public function searchAction()
    {
        $this->initializeRouteParams();
        $searchString = $this->getRequest()->getQuery(self::QUERY_PARAM_SEARCH);
        $start = $this->getRequest()->getQuery(self::QUERY_PARAM_SEARCH_START);
        $end = $this->getRequest()->getQuery(self::QUERY_PARAM_SEARCH_END);
        $resultData = null;
        $motTestDetails = null;

        if ($searchString) {
            try {
                $resultData = $this->getDataFromApi(
                    $this->getSearchApiPath($this->motTestNumber, $searchString, $start, $end)
                );

                $this->getPerformMotTestAssertion()->assertGranted($resultData['motTest']);

                $rfrs = $this->getReasonsForRejection($resultData);
                if (empty($rfrs)) {
                    $this->addErrorMessages(self::NO_SEARCH_RESULTS_FOUND_ERROR_MESSAGE);
                }

                $motTestDetails = $this->getMotTest($resultData);
            } catch (RestApplicationException $e) {
                $this->addErrorMessages($e->getDisplayMessages());
            }
        } else {
            $this->addErrorMessages(self::NO_SEARCH_STRING_ERROR_MESSAGE);
        }

        if (null === $motTestDetails) {
            return $this->redirect()->toRoute(
                'mot-test/test-item-selector', ['motTestNumber' => $this->motTestNumber]
            );
        }

        return new ViewModel(
            [
                'isMotContingency'    => $this->getContingencySessionManager()->isMotContingency(),
                'reasonsForRejection' => $this->getReasonsForRejection($resultData),
                'motTestDetails'      => $motTestDetails,
                'motTestNumber'       => $this->motTestNumber,
                'searchString'        => $searchString,
                'hasMore'             => $this->getSearchHasMoreResults($resultData),
                'start'               => $start
            ]
        );
    }

    public function addReasonForRejectionAction()
    {
        $request = $this->getRequest();
        $this->initializeRouteParamsWithRfrId();

        $type = $request->getPost('type');
        $locationLateral = $request->getPost('locationLateral');
        $locationLongitudinal = $request->getPost('locationLongitudinal');
        $locationVertical = $request->getPost('locationVertical');
        $comment = $request->getPost('comment');
        $failureDangerous = $request->getPost('failureDangerous') ? true : false;
        $searchString = $request->getPost('searchString');

        if ($request->isPost()) {
            try {
                $apiPath = sprintf(self::MOT_TEST_RFR_API_PATH_FORMAT, $this->motTestNumber);

                $data = [
                    'rfrId'                => $this->rfrId,
                    'type'                 => $type,
                    'locationLateral'      => ($locationLateral) ? $locationLateral : null,
                    'locationLongitudinal' => ($locationLongitudinal) ? $locationLongitudinal : null,
                    'locationVertical'     => ($locationVertical) ? $locationVertical : null,
                    'comment'              => ($comment) ? $comment : null,
                    'failureDangerous'     => $failureDangerous,
                ];

                $this->getRestClient()->postJson($apiPath, $data);
            } catch (RestApplicationException $e) {
                return $this->ajaxResponse()->ok(
                    [
                        'model'    => $request->getPost()->getArrayCopy(),
                        'messages' => $e->getDisplayMessages()
                    ]
                );
            }
        }

        if ($this->rfrId == 0) {
            $response = $this->ajaxResponse()->redirectToRoute(
                'mot-test/test-item-selector', ['motTestNumber' => $this->motTestNumber]
            );
        } else {
            if ($searchString) {
                $response = $this->ajaxResponse()->redirectToRoute(
                    'mot-test/test-item-selector-search', ['motTestNumber' => $this->motTestNumber],
                    ['query' => ['search' => $searchString]]
                );
            } else {
                $response = $this->ajaxResponse()->redirectToRoute(
                    'mot-test/test-item-selector',
                    [
                        'motTestNumber'     => $this->motTestNumber,
                        'tis-id' => $this->testItemSelectorId,
                    ]
                );
            }
        }
        return $response;
    }

    public function editReasonForRejectionAction()
    {
        $request = $this->getRequest();
        $this->initializeRouteParamsWithRfrId();

        $locationLateral = $request->getPost('locationLateral');
        $locationLongitudinal = $request->getPost('locationLongitudinal');
        $locationVertical = $request->getPost('locationVertical');
        $comment = $request->getPost('comment');
        $failureDangerous = $request->getPost('failureDangerous') ? true : false;

        if ($request->isPost()) {
            try {
                $apiPath = sprintf(self::MOT_TEST_RFR_API_PATH_FORMAT, $this->motTestNumber);

                $data = [
                    'id'                   => $this->rfrId,
                    'locationLateral'      => ($locationLateral) ? $locationLateral : null,
                    'locationLongitudinal' => ($locationLongitudinal) ? $locationLongitudinal : null,
                    'locationVertical'     => ($locationVertical) ? $locationVertical : null,
                    'comment'              => ($comment) ? $comment : null,
                    'failureDangerous'     => $failureDangerous,
                ];

                $this->getRestClient()->postJson($apiPath, $data);
            } catch (RestApplicationException $e) {
                return $this->ajaxResponse()->ok(
                    [
                        'model'    => $request->getPost()->getArrayCopy(),
                        'messages' => $e->getDisplayMessages()
                    ]
                );
            }
        }
        return $this->ajaxResponse()->redirectToRoute('mot-test', ['motTestNumber' => $this->motTestNumber]);
    }

    /**
     * Get suggested RFR groups for given vehicle class
     *
     * @return mixed
     */
    public function suggestionsAction()
    {
        $motTestNumber = $this->params()->fromRoute('motTestNumber');
        $data = $this->getRestClient()
            ->get(
                UrlBuilder::of()
                    ->testItemCategoryName()
                    ->routeParam('motTestNumber', $motTestNumber)
                    ->toString()
            );

        return $this->ajaxResponse()->ok($data['data']);
    }

    protected function getDataFromApi($path)
    {
        $result = $this->getRestClient()->get($path);
        return $result['data'];
    }

    protected function getMotTest($data)
    {
        $motTest = ArrayUtils::tryGet($data, 'motTest', false);
        if (!$motTest) {
            return null;
        }

        return $motTest;
    }

    protected function getTestItemSelectors($data)
    {
        return $data['testItemSelectors'];
    }

    protected function getReasonsForRejection($data)
    {
        return $data['reasonsForRejection'];
    }

    protected function getSearchHasMoreResults($data)
    {
        return $data['searchDetails']['hasMore'];
    }

    /**
     * Generates the breadcrumb links used in the RFR screens. If the user is
     * at the RFR home screen, then no breadcrumb links will be returned
     *
     * @param array $data the test item selectors
     * @return array
     */
    protected function getBreadcrumbTestItemSelectors($data)
    {
        $currentItemSelector = $data['testItemSelector'];
        $parentItemSelectors = $data['parentTestItemSelectors'];

        if (empty($parentItemSelectors)) {
            return [];
        }

        $breadcrumbItemSelectors = array_merge([$currentItemSelector], $parentItemSelectors);
        return array_reverse($breadcrumbItemSelectors);
    }

    protected function getTestItemSelectorApiPath()
    {
        return sprintf(
            self::TEST_ITEM_SELECTOR_API_PATH_FORMAT,
            $this->motTestNumber,
            $this->testItemSelectorId
        );
    }

    protected function getSearchApiPath($motTestNumber, $searchString, $start, $end)
    {
        return sprintf(
            self::TEST_ITEM_SELECTOR_RFR_SEARCH_API_PATH_FORMAT,
            $motTestNumber,
            $searchString,
            $start,
            $end
        );
    }

    /**
     * @return WebPerformMotTestAssertion
     */
    private function getPerformMotTestAssertion()
    {
        return $this->getServiceLocator()->get(WebPerformMotTestAssertion::class);
    }

    /**
     * @return ContingencySessionManager
     */
    private function getContingencySessionManager()
    {
        return $this->serviceLocator->get(ContingencySessionManager::class);
    }
}
