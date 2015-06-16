<?php

namespace DvsaCommon\Dto\Search;

use DvsaCommon\Constants\SearchParamConst;
use DvsaCommon\Dto\AbstractDataTransferObject;
use Zend\Stdlib\Parameters;

/**
 * Common class to pass search parameters to api
 */
class SearchParamsDto extends AbstractDataTransferObject
{
    /** @var string */
    private $format;
    /** @var int */
    private $rowsCount = SearchParamConst::DEF_ROWS_COUNT;
    /** @var int */
    private $pageNr = SearchParamConst::DEF_PAGE_NR;
    /** @var int */
    private $start;
    /** @var string */
    private $sortBy;
    /** @var string */
    private $sortDirection;
    /** @var boolean */
    private $isSearchRecent = false;
    /** @var string */
    private $searchTerm;
    /** @var string */
    private $filter;

    /** @var boolean */
    private $isApiGetData = true;
    /** @var boolean */
    private $isApiGetTotalCount = true;
    /** @var boolean    Tell to API use is Elastic Search enabled for this search */
    private $isEsEnable;

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     *
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSearchRecent()
    {
        return $this->isSearchRecent;
    }

    /**
     * @param boolean $isSearchRecent
     *
     * @return SearchParamsDto
     */
    public function setIsSearchRecent($isSearchRecent)
    {
        $this->isSearchRecent = $isSearchRecent;

        return $this;
    }

    /**
     * @return int
     */
    public function getPageNr()
    {
        return $this->pageNr;
    }

    /**
     * @param int $pageNr
     *
     * @return $this
     */
    public function setPageNr($pageNr)
    {
        $this->pageNr = $pageNr;

        return $this;
    }

    /**
     * @return int
     */
    public function getRowsCount()
    {
        return $this->rowsCount;
    }

    /**
     * @param int|null $rowsCount Set null to get all records
     *
     * @return $this
     */
    public function setRowsCount($rowsCount = null)
    {
        $this->rowsCount = $rowsCount;

        return $this;
    }

    /**
     * @return string
     */
    public function getSearchTerm()
    {
        return $this->searchTerm;
    }

    /**
     * @param string $searchTerm
     *
     * @return SearchParamsDto
     */
    public function setSearchTerm($searchTerm)
    {
        $this->searchTerm = $searchTerm;

        return $this;
    }

    /**
     * @return string
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }

    /**
     * @param string $sortColumnId
     *
     * @return $this
     */
    public function setSortBy($sortColumnId)
    {
        $this->sortBy = $sortColumnId;

        return $this;
    }

    /**
     * @return string
     */
    public function getSortDirection()
    {
        return $this->sortDirection;
    }

    /**
     * @param string $sortDirection
     *
     * @return SearchParamsDto
     */
    public function setSortDirection($sortDirection)
    {
        $this->sortDirection = $sortDirection;

        return $this;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param int $start
     *
     * @return SearchParamsDto
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param mixed $filter
     *
     * @return SearchParamsDto
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isApiGetData()
    {
        return $this->isApiGetData;
    }

    /**
     * @param boolean $value
     *
     * @return $this
     */
    public function setIsApiGetData($value)
    {
        $this->isApiGetData = $value;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isApiGetTotalCount()
    {
        return $this->isApiGetTotalCount;
    }

    /**
     * @param boolean $isNeedGetTotalCount
     *
     * @return $this
     */
    public function setIsApiGetTotalCount($isNeedGetTotalCount)
    {
        $this->isApiGetTotalCount = $isNeedGetTotalCount;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isEsEnabled()
    {
        return $this->isEsEnable;
    }

    /**
     * @param boolean $value
     *
     * @return $this
     */
    public function setIsEsEnabled($value)
    {
        $this->isEsEnable = $value;

        return $this;
    }

    public function toQueryParams()
    {
        return new Parameters(
            array_filter(
                [
                    SearchParamConst::ROW_COUNT      => $this->getRowsCount(),
                    SearchParamConst::PAGE_NR        => $this->getPageNr(),
                    SearchParamConst::SORT_BY        => $this->getSortBy(),
                    SearchParamConst::SORT_DIRECTION => $this->getSortDirection(),
                ]
            )
        );
    }
}
