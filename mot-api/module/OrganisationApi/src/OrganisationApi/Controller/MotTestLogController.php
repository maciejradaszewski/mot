<?php

namespace OrganisationApi\Controller;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaElasticSearch\Service\ElasticSearchService;
use DvsaEntities\DqlBuilder\SearchParam\MotTestSearchParam;
use OrganisationApi\Service\MotTestLogService;

/**
 * Class MotTestLogController
 * @package OrganisationApi\Controller
 */
class MotTestLogController extends AbstractDvsaRestfulController
{
    const ERR_ORG_ID = 'Invalid organisation Id entered.';

    /** @var MotTestLogService  */
    private $motTestLogService;
    /** @var ElasticSearchService  */
    private $elasticSearchService;
    /** @var EntityManager  */
    private $entityManager;

    public function __construct(
        MotTestLogService $motTestLogService,
        ElasticSearchService $elasticSearchService,
        EntityManager $entityManager
    ) {
        $this->motTestLogService = $motTestLogService;
        $this->elasticSearchService = $elasticSearchService;
        $this->entityManager = $entityManager;
    }

    public function summaryAction()
    {
        $orgId = $this->params()->fromRoute('id');

        return ApiResponse::jsonOk(
            $this->motTestLogService->getMotTestLogSummaryForOrganisation($orgId)
        );
    }

    public function create($data)
    {
        $orgId = (int) $this->params()->fromRoute('id');

        $searchParams = new MotTestSearchParam($this->entityManager);
        $searchParams
            ->fromDto(DtoHydrator::jsonToDto($data))
            ->setOrganisationId($orgId);

        if ($orgId <= 0) {
            return $this->returnBadRequestResponseModel(
                'invalid organisation identifier',
                self::ERROR_CODE_REQUIRED,
                self::ERR_ORG_ID
            );
        }

        return ApiResponse::jsonOk($this->elasticSearchService->findTestsLog($searchParams));
    }
}
