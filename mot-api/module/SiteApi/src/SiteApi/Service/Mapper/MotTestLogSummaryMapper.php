<?php

namespace SiteApi\Service\Mapper;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use DvsaCommon\Dto\Site\MotTestLogSummaryDto;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Service\Mapper\AbstractApiMapper;

/**
 * MotTestLog Mapper.
 */
class MotTestLogSummaryMapper extends AbstractApiMapper
{
    /**
     * @param array $logSummary contains count of tests for different intervals
     *
     * @return MotTestLogSummaryDto
     */
    public function toDto($logSummary)
    {
        if (!is_array($logSummary) || empty($logSummary)) {
            throw new InvalidArgumentException('Expect array and not empty parameter');
        }

        $dto = new MotTestLogSummaryDto();
        $dto
            ->setYear(ArrayUtils::tryGet($logSummary, 'year', 0))
            ->setMonth(ArrayUtils::tryGet($logSummary, 'month', 0))
            ->setWeek(ArrayUtils::tryGet($logSummary, 'week', 0))
            ->setToday(ArrayUtils::tryGet($logSummary, 'today', 0));

        return $dto;
    }
}
