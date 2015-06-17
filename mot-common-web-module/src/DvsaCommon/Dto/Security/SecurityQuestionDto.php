<?php

namespace DvsaCommon\Dto\Security;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;

class SecurityQuestionDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;

    /** @var  string $text */
    private $text;
    /** @var  int $group */
    private $group;
    /** @var  int $displayOrder */
    private $displayOrder;

    /**
     * @param $displayOrder
     *
     * @return $this
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;
        return $this;
    }

    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }

    /**
     * @param $group
     *
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }

    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    public function getText()
    {
        return $this->text;
    }
}
