<?php

namespace DvsaMotApi\Controller;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaElasticSearch\Service\ElasticSearchService;
use DvsaEntities\DqlBuilder\SearchParam\MotTestLogSearchParam;
use DvsaMotApi\Service\TesterMotTestLogService;

class TesterMotTestLogController extends AbstractDvsaRestfulController
{
    const ERR_TESTER_ID = 'Invalid tester Id entered.';

    /**
     * @var TesterMotTestLogService
     */
    private $motTestLogService;

    /**
     * @var ElasticSearchService
     */
    private $elasticSearchService;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(
        TesterMotTestLogService $motTestLogService,
        ElasticSearchService $elasticSearchService,
        EntityManager $entityManager
    ) {
        $this->motTestLogService = $motTestLogService;
        $this->elasticSearchService = $elasticSearchService;
        $this->entityManager = $entityManager;
    }

    public function summaryAction()
    {
        $testerId = $this->params()->fromRoute('id');

        return ApiResponse::jsonOk(
            $this->motTestLogService->getMotTestLogSummaryForTester($testerId)
        );
    }

    public function logDataAction()
    {
        $testerId = (int) $this->params()->fromRoute('id');
        if ($testerId <= 0) {
            return $this->returnBadRequestResponseModel(
                self::ERR_TESTER_ID,
                self::ERROR_CODE_REQUIRED,
                self::ERR_TESTER_ID
            );
        }

        $postData = $this->processBodyContent($this->getRequest());

        $searchParams = new MotTestLogSearchParam($this->entityManager);
        $searchParams
            ->fromDto(DtoHydrator::jsonToDto($postData))
            ->setTesterId($testerId);

        return ApiResponse::jsonOk($this->elasticSearchService->findTesterTestsLog($searchParams));
    }
}
