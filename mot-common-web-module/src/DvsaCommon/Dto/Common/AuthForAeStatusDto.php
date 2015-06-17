<?php

namespace DvsaCommon\Dto\Common;

use DvsaCommon\Dto\AbstractDataTransferObject;

/**
 * Class AuthForAeStatusDto
 *
 * @package DvsaCommon\Dto\Common
 */
class AuthForAeStatusDto extends AbstractDataTransferObject
{
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
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
}
