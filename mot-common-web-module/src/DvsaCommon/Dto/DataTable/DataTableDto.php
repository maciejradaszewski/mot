<?php

namespace DvsaCommon\Dto\DataTable;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Utility\ArrayUtils;

/**
 * Class DataTableDto
 * @package DvsaCommon\Dto\Event
 */
class DataTableDto extends AbstractDataTransferObject
{
    const FLD_DISPLAY_START     = 'iDisplayStart';
    const FLD_DISPLAY_LENGTH    = 'iDisplayLength';
    const FLD_SORT_COL          = 'iSortCol_0';
    const FLD_SORT_DIR          = 'sSortDir_0';

    /**
     * @var string $pageNumber
     */
    private $pageNumber;
    /**
     * @var int $iDisplayStart
     */
    private $displayStart = 0;
    /**
     * @var int $iDisplayLength
     */
    private $displayLength = 10;
    /**
     * @var int $iSortCol
     */
    private $sortCol = 0;
    /**
     * @var string $sSortDir ASC/DESC
     */
    private $sortDir = 'DESC';

    /**
     * @return string
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * @param string $pageNumber
     * @return $this
     */
    public function setPageNumber($pageNumber)
    {
        $this->pageNumber = $pageNumber;
        return $this;
    }

    /**
     * @return int
     */
    public function getDisplayStart()
    {
        return $this->displayStart;
    }

    /**
     * @param int $displayStart
     * @return $this
     */
    public function setDisplayStart($displayStart)
    {
        $this->displayStart = $displayStart;
        return $this;
    }

    /**
     * @return int
     */
    public function getDisplayLength()
    {
        return $this->displayLength;
    }

    /**
     * @param int $displayLength
     * @return $this
     */
    public function setDisplayLength($displayLength)
    {
        $this->displayLength = $displayLength;
        return $this;
    }

    /**
     * @return int
     */
    public function getSortCol()
    {
        return $this->sortCol;
    }

    /**
     * @param int $sortCol
     * @return $this
     */
    public function setSortCol($sortCol)
    {
        $this->sortCol = $sortCol;
        return $this;
    }

    /**
     * @return string
     */
    public function getSortDir()
    {
        return $this->sortDir;
    }

    /**
     * @param string $sortDir
     * @return $this
     */
    public function setSortDir($sortDir)
    {
        $this->sortDir = $sortDir;
        return $this;
    }
}
