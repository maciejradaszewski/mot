<?php

namespace DvsaMotApi\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaMotApi\Service\SurveyService;

/**
 * Class SurveyController
 * @package DvsaMotApi\Controller
 */
class SurveyController extends AbstractDvsaRestfulController
{
    /**
     * @var SurveyService $surveyService
     */
    private $surveyService;

    /**
     * SurveyController constructor.
     * @param SurveyService $surveyService
     */
    public function __construct(SurveyService $surveyService)
    {
        $this->surveyService = $surveyService;
    }

    /**
     * @param mixed $data
     */
    public function create($data)
    {
        $response = $this->surveyService->createSurveyResult($data);

        return ApiResponse::jsonOk($response);
    }
}