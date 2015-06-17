<?php

namespace DvsaCommon\Dto\CommonTrait;

trait CommonIdentityDtoTrait
{
    /** @var  integer */
    private $id;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}
