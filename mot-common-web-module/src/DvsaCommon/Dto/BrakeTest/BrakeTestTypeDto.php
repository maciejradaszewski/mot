<?php

namespace DvsaCommon\Dto\BrakeTest;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;
use DvsaCommon\Dto\CommonTrait\EnumTypeDtoTrait;

/**
 * Class BrakeTestTypeDto
 *
 * @package DvsaCommon\Dto\BrakeTest
 */
class BrakeTestTypeDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;
    use EnumTypeDtoTrait;

    /** @var string */
    private $name;

    /**
     * @return $this
     */
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
