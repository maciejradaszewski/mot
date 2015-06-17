<?php

namespace DvsaElasticSearch\Query;

use DvsaCommonApi\Model\SearchParam;

/**
 * Class SuperSearchQuery
 *
 * I answer all the type of search and redirect to the good service.
 *
 * @package DvsaElasticSearch\Query
 */
class SuperSearchQuery {

    /**
     * @param SearchParam   $searchParams
     * @param IFbQuery        $databaseFallBack
     */
    public static function execute($searchParams, $databaseFallBack)
    {
        return $databaseFallBack->execute($searchParams);
    }
}