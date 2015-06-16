<?php

namespace Report\Table;

use Zend\Stdlib\AbstractOptions;

/**
 * Contains table settings
 *
 * @package Report\Table
 */
class TableOptions extends AbstractOptions
{
    /**
     * @var int
     */
    private $itemsPerPage = 10;
    /**
     * @var array
     */
    private $itemsPerPageOptions = [10, 25, 50];
    /**
     * @var string
     */
    private $tableViewScript = 'table/table';
    /**
     * @var string
     */
    private $footerViewScript = 'table/footer';

    /**
     * @return int
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * @return $this
     */
    public function setItemsPerPage($itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;
        return $this;
    }

    /**
     * @return array
     */
    public function getItemsPerPageOptions()
    {
        return $this->itemsPerPageOptions;
    }

    /**
     * @return $this
     */
    public function setItemsPerPageOptions(array $itemsPerPageOptions)
    {
        $this->itemsPerPageOptions = $itemsPerPageOptions;
        return $this;
    }

    /**
     * @return string
     */
    public function getTableViewScript()
    {
        return $this->tableViewScript;
    }

    /**
     * @return $this
     */
    public function setTableViewScript($tableViewScript)
    {
        $this->tableViewScript = $tableViewScript;
        return $this;
    }

    /**
     * @return string
     */
    public function getFooterViewScript()
    {
        return $this->footerViewScript;
    }

    /**
     * @return $this
     */
    public function setFooterViewScript($footerViewScript)
    {
        $this->footerViewScript = $footerViewScript;
        return $this;
    }
}
