<?php

namespace DvsaMotTest\Controller;

use DvsaCommon\Constants\SearchParamConst;
use DvsaCommon\Dto\Organisation\MotTestLogSummaryDto;
use DvsaCommon\Dto\Search\MotTestSearchParamsDto;
use DvsaCommon\Dto\Search\SearchResultDto;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;
use Organisation\Controller\MotTestLogController;
use DvsaMotTest\ViewModel\TesterMotTestLog\TesterMotTestLogViewModel;
use Zend\View\Model\ViewModel;

/**
 * Class TesterMotTestLogController
 * @package DvsaMotTest\Controller
 */
class TesterMotTestLogController extends MotTestLogController
{
    public function indexAction()
    {
        $testerId = $this->getIdentity()->getUserId();

        $this->request = $this->getRequest();

        $motTestLogs = $this->getLogSummary($testerId);

        //  logical block :: prepare models for view
        $viewModel = new TesterMotTestLogViewModel($motTestLogs);
        $viewModel->parseData($this->request->getQuery());

        $formModel = $viewModel->getFormModel();
        if ($formModel->isValid()) {
            //  logical block :: create object with parameters for sending to api   --
            $searchParams = $this->prepareSearchParams($formModel);
            if ($searchParams->getRowsCount() === 0) {
                $searchParams->setRowsCount($viewModel->getTable()->getTableOptions()->getItemsPerPage());
            }

            $apiResult = $this->getTesterLogDataBySearchCriteria($testerId, $searchParams);

            $totalRecordsCount = (int) $apiResult->getTotalResultCount();
            if ($totalRecordsCount === 0) {
                $this->addErrorMessages(self::ERR_NO_DATA);
            }

            //  logical block :: set search parameters and date to table
            $viewModel->getTable()
                ->setSearchParams($apiResult->getSearched())
                ->setRowsTotalCount($apiResult->getTotalResultCount())
                ->setData($apiResult->getData());

            //  logical block :: set search parameters to date range
            $viewModel->getFilterBuilder()
                ->setQueryParams($apiResult->getSearched()->toQueryParams());
        }

        //  logic block: prepare view
        $this->layout('layout/layout-govuk.phtml');

        $breadcrumbs = [
            'Performance dashboard' => PersonUrlBuilderWeb::of()->stats(),
            'Tester Test logs' => '',
        ];
        $this->layout()->setVariable('breadcrumbs', ['breadcrumbs' => $breadcrumbs]);

        $this->layout()->setVariable('pageTitle', $this->getIdentity()->getDisplayName());
        $this->layout()->setVariable('pageSubTitle', 'Test logs of Tester');

        return new ViewModel(
            [
                'viewModel' => $viewModel,
            ]
        );
    }

    public function downloadCsvAction()
    {
        $testerId = $this->getIdentity()->getUserId();

        //  --  create object with parameters for sending to api   --
        $searchParams = $this->prepareSearchParams();
        $searchParams
            ->setFormat(SearchParamConst::FORMAT_DATA_CSV)
            ->setRowsCount(self::MAX_TESTS_COUNT)
            ->setIsApiGetTotalCount(false)
            ->setIsApiGetData(true);

        $apiResult = $this->getTesterLogDataBySearchCriteria($testerId, $searchParams);

        //  --  define content of csv file  --
        if ($apiResult->getResultCount() > 0) {
            $csvBody = $this->prepareCsvBody($apiResult->getData());
        } else {
            $csvBody = '';
        }

        //  --  define csv file name     --
        $fileName = 'test-log-' .
            (new \DateTime('@' . $searchParams->getDateFromTs()))->format('dmY') . '-' .
            (new \DateTime('@' . $searchParams->getDateToTs()))->format('dmY') . '.csv';

        //  --  set response    --
        /** @var \Zend\Http\Response $response */
        $response = $this->getResponse();

        $headers = $response->getHeaders();
        $headers->clearHeaders()
            ->addHeaderLine('Content-Type', 'text/csv; charset=utf-8')
            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->addHeaderLine('Accept-Ranges', 'bytes')
            ->addHeaderLine('Content-Length', strlen($csvBody))
            ->addHeaderLine('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->addHeaderLine('Pragma', 'no-cache');

        $response->setContent($csvBody);

        return $response;
    }

    /**
     * @param $testerId
     * @param MotTestSearchParamsDto $searchParams
     * @return SearchResultDto|null
     */
    protected function getTesterLogDataBySearchCriteria($testerId, MotTestSearchParamsDto $searchParams)
    {
        try {
            return $this->mapperFactory->MotTestLog->getTesterData($testerId, $searchParams);
        } catch (RestApplicationException $e) {
            $this->addErrorMessages($e->getDisplayMessages());
        }

        return null;
    }

    /**
     * Get mot tests log summary information from api (year, prev month, prev week, today)
     *
     * @param $testerId
     * @return MotTestLogSummaryDto|null
     */
    protected function getLogSummary($testerId)
    {
        try {
            return $this->mapperFactory->MotTestLog->getTesterSummary($testerId);
        } catch (RestApplicationException $e) {
            $this->addErrorMessages($e->getDisplayMessages());
        }

        return null;
    }
}
