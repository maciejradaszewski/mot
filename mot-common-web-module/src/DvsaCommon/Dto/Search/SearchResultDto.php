<?php

namespace DvsaCommon\Dto\Search;

use DvsaCommon\Dto\AbstractDataTransferObject;

/**
 * Common class to pass search result from API
 */
class SearchResultDto extends AbstractDataTransferObject
{
    /** @var  int */
    private $resultCount;
    /** @var  int */
    private $totalResultCount;
    /** @var  array */
    private $data;

    /** @var  SearchParamsDto */
    private $searched;
    /** @var  boolean */
    private $isElasticSearch;


    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isElasticSearch()
    {
        return $this->isElasticSearch;
    }

    /**
     * @param boolean $isElasticSearch
     *
     * @return $this
     */
    public function setIsElasticSearch($isElasticSearch)
    {
        $this->isElasticSearch = $isElasticSearch;
        return $this;
    }

    /**
     * @return int
     */
    public function getResultCount()
    {
        return $this->resultCount;
    }

    /**
     * @param int $resultCount
     *
     * @return $this
     */
    public function setResultCount($resultCount)
    {
        $this->resultCount = $resultCount;
        return $this;
    }

    /**
     * @return SearchParamsDto
     */
    public function getSearched()
    {
        return $this->searched;
    }

    /**
     * @param SearchParamsDto $searched
     *
     * @return $this
     */
    public function setSearched($searched)
    {
        $this->searched = $searched;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalResultCount()
    {
        return $this->totalResultCount;
    }

    /**
     * @param int $totalResultCount
     *
     * @return $this
     */
    public function setTotalResultCount($totalResultCount)
    {
        $this->totalResultCount = $totalResultCount;
        return $this;
    }
}
