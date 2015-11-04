<?php

namespace DvsaElasticSearch\Query;

use DvsaCommon\Dto\Search\SearchResultDto;
use DvsaCommonApi\Model\SearchParam;
use DvsaElasticSearch\Model\ESDocMotTest;
use DvsaEntities\DqlBuilder\SearchParam\MotTestSearchParam;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Repository\MotTestRepository;

/**
 * Class FbQueryMotTest
 *
 * I answer as a fallback for all MotTest that match the set search criteria.
 *
 * @package DvsaElasticSearch\Query
 */
class FbQueryMotTest implements IFbQuery
{
    /**
     * @param MotTestSearchParam $searchParams
     *
     * @return array
     */
    public function execute(SearchParam $searchParams)
    {

        $result = new SearchResultDto();
        $result->setSearched($searchParams->toDto());

        /* @var MotTestRepository $motRepo */
        $motRepo = $searchParams->getRepository(MotTest::class);

        if ($searchParams->getSearchRecent()) {
            $motTests = $motRepo->getLatestMotTestsBySiteNumber($searchParams->getSiteNumber());
            $totalResultCount = count($motTests);
        } else {
            $motTests = $motRepo->getMotTestSearchResult($searchParams);
            $totalResultCount = $motRepo->getMotTestSearchResultCount($searchParams);
        }

        $result
            ->setData($motTests)
            ->setResultCount(count($motTests))
            ->setTotalResultCount($totalResultCount);

        $result->setData((new ESDocMotTest())->asJson($result));

        return $result;
    }
}
