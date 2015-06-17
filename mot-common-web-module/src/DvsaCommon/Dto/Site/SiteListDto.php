<?php

namespace DvsaCommon\Dto\Site;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\Search\SiteSearchParamsDto;

/**
 * Class SiteListDto
 *
 * @package DvsaCommon\Dto\Site
 */
class SiteListDto extends AbstractDataTransferObject
{
    /** @var  int */
    private $totalResultCount;
    /** @var  array */
    private $data;
    /** @var  SiteSearchParamsDto */
    private $searched;

    /**
     * @return int
     */
    public function getTotalResultCount()
    {
        return $this->totalResultCount;
    }

    /**
     * @param int $totalResultCount
     * @return $this
     */
    public function setTotalResultCount($totalResultCount)
    {
        $this->totalResultCount = $totalResultCount;
        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function getSearched()
    {
        return $this->searched;
    }

    /**
     * @param SiteSearchParamsDto $searched
     * @return $this
     */
    public function setSearched($searched)
    {
        $this->searched = $searched;
        return $this;
    }

}
