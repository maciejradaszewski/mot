<?php

namespace Dvsa\Mot\Frontend\MotTestModule\Service;

use DvsaCommon\HttpRestJson\Client;

/**
 * Class SurveyService
 * @package DvsaMotTest\Service
 */
class SurveyService
{
    const API_URL = 'survey';

    /**
     * @var Client $restClient
     */
    private $restClient;

    /**
     * SurveyService constructor.
     * @param Client $restClient
     */
    public function __construct(
        Client $restClient
    ) {
        $this->restClient = $restClient;
    }

    /**
     * @param $data
     * @return mixed|string
     */
    public function submitSurveyResult($data)
    {
        $result = $this->restClient->post(self::API_URL, $data);
        return $result;
    }
}