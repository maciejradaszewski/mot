<?php

namespace DvsaCommon\Dto\Vehicle;

use DvsaCommon\Dto\AbstractDataTransferObject;

/**
 * Class ModelDto
 *
 * @package DvsaCommon\Dto\Vehicle
 */
class ModelDto extends AbstractDataTransferObject
{
    /** @var int */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $name;
    /** @var MakeDto */
    private $make;

    /**
     * @param int $id
     *
     * @return ModelDto
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
     * @param string $code
     *
     * @return ModelDto
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
     * @param string $name
     *
     * @return ModelDto
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
     * @param MakeDto $make
     *
     * @return ModelDto
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
}
