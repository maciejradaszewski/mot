<?php

namespace DvsaCommon\Dto\AreaOffice;

use DvsaCommon\Dto\AbstractDataTransferObject;

class AreaOfficeDto extends AbstractDataTransferObject
{
    /**
     * @var int
     */
    protected $siteId;

    /**
     * @var string
     */
    protected $siteNumber;

    /**
     * @var int
     */
    protected $aoNumber;

    /**
     * @var string
     */
    protected $name;

    /**
     * @return int
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @param int $siteId
     * @return AreaOfficeDto
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSiteNumber()
    {
        return $this->siteNumber;
    }

    /**
     * @param string $siteNumber
     * @return AreaOfficeDto
     */
    public function setSiteNumber($siteNumber)
    {
        $this->siteNumber = $siteNumber;
        return $this;
    }

    /**
     * @return int
     */
    public function getAoNumber()
    {
        return $this->aoNumber;
    }

    /**
     * @param int $aoNumber
     * @return AreaOfficeDto
     */
    public function setAoNumber($aoNumber)
    {
        $this->aoNumber = $aoNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return AreaOfficeDto
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}