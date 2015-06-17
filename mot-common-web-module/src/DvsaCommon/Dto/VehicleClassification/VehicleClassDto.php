<?php
namespace DvsaCommon\Dto\VehicleClassification;

use DvsaCommon\Dto\AbstractDataTransferObject;

/**
 * Class VehicleClassDto
 *
 * @package DvsaCommon\Dto\VehicleClassification
 */
class VehicleClassDto extends AbstractDataTransferObject
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var string */
    private $code;
    /** @var string */
    private $group;

    /**
     * @param $id
     *
     * @return VehicleClassDto
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $value
     *
     * @return VehicleClassDto
     */
    public function setName($value)
    {
        $this->name = $value;

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
     * @param $value
     *
     * @return VehicleClassDto
     */
    public function setCode($value)
    {
        $this->code = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param $value
     *
     * @return VehicleClassDto
     */
    public function setGroup($value)
    {
        $this->group = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }
}
