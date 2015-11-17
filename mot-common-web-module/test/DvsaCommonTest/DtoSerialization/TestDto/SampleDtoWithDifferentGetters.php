<?php

namespace DvsaCommonTest\DtoSerialization\TestDto;

use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class SampleDtoWithDifferentGetters implements ReflectiveDtoInterface
{
    /** @var  bool */
    private $hasYellow;

    /**
     * @var bool
     */
    private $active;

    /**
     * @return boolean
     */
    public function hasYellow()
    {
        return $this->hasYellow;
    }

    /**
     * @param boolean $hasYellow
     */
    public function setHasYellow($hasYellow)
    {
        $this->hasYellow = $hasYellow;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }
}
