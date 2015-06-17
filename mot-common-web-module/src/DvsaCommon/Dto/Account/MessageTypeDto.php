<?php

namespace DvsaCommon\Dto\Account;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;
use DvsaCommon\Dto\CommonTrait\EnumTypeDtoTrait;

class MessageTypeDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;
    use EnumTypeDtoTrait;

    /** @var  string */
    private $name;


    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
