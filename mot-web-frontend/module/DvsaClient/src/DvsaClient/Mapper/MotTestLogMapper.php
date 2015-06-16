<?php

namespace DvsaClient\Mapper;

use DvsaCommon\Dto\Search\MotTestSearchParamsDto;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilder;
use DvsaCommon\Utility\DtoHydrator;

/**
 * Provide functionality for receiving Mot Test Log data from api
 */
class MotTestLogMapper extends DtoMapper
{
    /**
     * @param int $orgId
     *
     * @return \DvsaCommon\Dto\Search\MotTestSearchParamsDto
     */
    public function getSummary($orgId)
    {
        $apiUrl = AuthorisedExaminerUrlBuilder::motTestLogSummary($orgId);

        return $this->get($apiUrl);
    }

    /**
     * @param int                    $orgId
     * @param MotTestSearchParamsDto $searchParams
     *
     * @return \DvsaCommon\Dto\Search\MotTestSearchParamsDto
     */
    public function getData($orgId, $data)
    {
        $apiUrl = AuthorisedExaminerUrlBuilder::motTestLog($orgId)->toString();

        return $this->post($apiUrl, DtoHydrator::dtoToJson($data));
    }
}
