<?php

namespace DvsaCommon\Dto\Common;

use DvsaCommon\Dto\AbstractDataTransferObject;

abstract class AbstractStaticDataDto extends AbstractDataTransferObject
{
    /** @var int */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $name;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return AbstractStaticDataDto
     */
    public function setCode($code)
    {
        $this->code = $code;

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
     * @param int $id
     *
     * @return AbstractStaticDataDto
     */
    public function setId($id)
    {
        $this->id = $id;

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
     *
     * @return AbstractStaticDataDto
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
