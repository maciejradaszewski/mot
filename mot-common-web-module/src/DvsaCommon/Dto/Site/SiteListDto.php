<?php

namespace DvsaCommon\Dto\Site;

use DvsaCommon\Dto\AbstractDataTransferObject;

/**
 * Class SiteListDto
 *
 * @package DvsaCommon\Dto\Site
 */
class SiteListDto extends AbstractDataTransferObject
{
    /** @var  int */
    private $totalResult;
    /** @var  SiteSearchDto[] */
    private $sites;

    /**
     * @return int
     */
    public function getTotalResult()
    {
        return $this->totalResult;
    }

    /**
     * @param int $totalResult
     * @return $this
     */
    public function setTotalResult($totalResult)
    {
        $this->totalResult = $totalResult;
        return $this;
    }

    /**
     * @return SiteSearchDto[]
     */
    public function getSites()
    {
        return $this->sites;
    }

    /**
     * @param SiteSearchDto[] $sites
     * @return $this
     */
    public function setSites($sites)
    {
        $this->sites = $sites;
        return $this;
    }
}
