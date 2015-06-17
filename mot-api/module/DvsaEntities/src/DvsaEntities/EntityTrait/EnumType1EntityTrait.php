<?php

namespace DvsaEntities\EntityTrait;

/**
 * EnumType1 common properties.
 */
trait EnumType1EntityTrait
{
    /**
     * @var string code for given enum entity auto-generated found in \DvsaCommon\Enum\
     *
     * @ORM\Column(name="code", type="string", length=5, nullable=false)
     */
    private $code;

    /**
     * @param string $code
     *
     * @return $this
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
}
