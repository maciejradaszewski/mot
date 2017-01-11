<?php
namespace Dvsa\Mot\Api\StatisticsApi\TesterQualityInformation\TesterPerformance\Tester\QueryBuilder;

use Dvsa\Mot\Api\StatisticsApi\TesterQualityInformation\TesterPerformance\Common\QueryBuilder\ManyGroupsStatisticsQueryBuilder;

class TesterManyGroupsStatisticsQueryBuilder extends ManyGroupsStatisticsQueryBuilder
{
    protected $index = "USE INDEX (`mot_test_person_id_started_date_completed_date_idx`)";
    protected $where = "AND `test`.`person_id` = :testerId ";
}