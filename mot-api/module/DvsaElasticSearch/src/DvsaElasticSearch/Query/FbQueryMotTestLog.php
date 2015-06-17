<?php

namespace DvsaElasticSearch\Query;

use DvsaCommon\Dto\Search\SearchResultDto;
use DvsaCommonApi\Model\SearchParam;
use DvsaElasticSearch\Model\ESDocMotTestLog;
use DvsaEntities\DqlBuilder\SearchParam\MotTestSearchParam;

/**
 * I answer as a fallback for all MotTestLog that match the set search criteria.
 */
class FbQueryMotTestLog implements IFbQuery
{
    /**
     * @param MotTestSearchParam $searchParams
     *
     * @return SearchResultDto
     */
    public function execute(SearchParam $searchParams)
    {
        $resultDto = new SearchResultDto();
        $resultDto
            ->setSearched($searchParams->toDto())
            ->setIsElasticSearch(false);

        $repo = $searchParams->getRepository();

        if ($searchParams->isApiGetTotalCount()) {
            $dbResult = $repo->getMotTestLogsResultCount($searchParams);

            $resultDto->setTotalResultCount($dbResult['count']);
        }

        if ($searchParams->isApiGetData()) {
            $dbResult = $repo->getMotTestLogsResult($searchParams);

            $resultDto
                ->setResultCount(count($dbResult))
                ->setData($dbResult);

            $resultDto->setData((new ESDocMotTestLog())->asJson($resultDto));
        }

        return $resultDto;
    }
}
