<?php

namespace DvsaCommon\Dto\Equipment;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;
use DvsaCommon\Dto\Site\SiteDto;

/**
 * Class EquipmentDto
 *
 * @package DvsaCommon\Dto\Equipment
 */
class EquipmentDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;

    /** @var EquipmentModelDto */
    private $model;
    /** @var  string */
    private $serialNumber;
    /** @var  integer   timestamp */
    private $dateAdded;
    /** @var  SiteDto */
    private $site;

    /**
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return EquipmentModelDto
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return $this
     */
    public function setSerialNumber($serialNumber)
    {
        $this->serialNumber = $serialNumber;
        return $this;
    }

    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    /**
     * @return $this
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
        return $this;
    }

    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @return $this
     */
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * @return SiteDto
     */
    public function getSite()
    {
        return $this->site;
    }
}
