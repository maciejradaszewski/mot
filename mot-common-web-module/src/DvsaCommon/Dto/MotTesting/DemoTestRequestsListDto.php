<?php

namespace DvsaCommon\Dto\MotTesting;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\Search\DemoTestRequestsSearchParamsDto;

/**
 * Class DemoTestRequestsListDto
 */
class DemoTestRequestsListDto extends AbstractDataTransferObject
{
    /** @var  int */
    private $totalResultCount;
    /** @var  DemoTestRequestsDto[] */
    private $data = [];
    /** @var  DemoTestRequestsSearchParamsDto */
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
     * @return DemoTestRequestsDto[]
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param DemoTestRequestsDto[] $data
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
     * @param DemoTestRequestsSearchParamsDto $searched
     * @return $this
     */
    public function setSearched($searched)
    {
        $this->searched = $searched;
        return $this;
    }

}
