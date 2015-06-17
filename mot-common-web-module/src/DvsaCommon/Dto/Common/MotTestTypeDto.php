<?php

namespace DvsaCommon\Dto\Common;

use DvsaCommon\Dto\AbstractDataTransferObject;

class MotTestTypeDto extends AbstractDataTransferObject
{
    /** @var int $id */
    private $id;
    /** @var string $code */
    private $code;

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
     * @return MotTestTypeDto
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
     * @return MotTestTypeDto
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
