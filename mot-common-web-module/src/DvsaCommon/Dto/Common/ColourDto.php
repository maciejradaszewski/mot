<?php

namespace DvsaCommon\Dto\Common;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\EnumTypeDtoTrait;

/**
 * Class ColourDto
 */
class ColourDto extends AbstractDataTransferObject
{
    use EnumTypeDtoTrait;

    /** @var string */
    private $name;

    /**
     * @param string $name
     *
     * @return ColourDto
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
}
