<?php

namespace Organisation\UpdateAeProperty;

use Core\TwoStepForm\FormContextInterface;

class UpdateAeContext implements FormContextInterface
{
    private $aeId;
    private $propertyName;

    public function __construct($aeId, $propertyName)
    {
        $this->aeId = $aeId;
        $this->propertyName = $propertyName;
    }

    public function getAeId()
    {
        return $this->aeId;
    }

    public function getPropertyName()
    {
        return $this->propertyName;
    }
}
