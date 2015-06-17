<?php

namespace DvsaCommon\Dto\Vehicle;

use DvsaCommon\Dto\AbstractDataTransferObject;

/**
 * Class ModelDetailDto
 *
 * @package DvsaCommon\Dto\Vehicle
 */
class ModelDetailDto extends AbstractDataTransferObject
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var string */
    private $code;
    /** @var ModelDto */
    private $model;
    /** @var MakeDto */
    private $make;

    public function __construct()
    {
        $this->model = new ModelDto();
        $this->make = new MakeDto();
    }

    /**
     * @param int $id
     *
     * @return ModelDetailDto
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
     * @param string $name
     *
     * @return ModelDetailDto
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * @param string $code
     *
     * @return ModelDetailDto
     */
    public function setCode($code)
    {
        $this->code = $code;

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
     * @param MakeDto $make
     *
     * @return ModelDetailDto
     */
    public function setMake($make)
    {
        $this->make = $make;

        return $this;
    }

    /**
     * @return MakeDto
     */
    public function getMake()
    {
        return $this->make;
    }

    /**
     * @param ModelDto $model
     *
     * @return ModelDetailDto
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return ModelDto
     */
    public function getModel()
    {
        return $this->model;
    }

    public function getMakeAndModel()
    {
        return $this->getMake()->getName() . ', ' . $this->getModel()->getName();
    }
}
