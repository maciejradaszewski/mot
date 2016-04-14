<?php

namespace Site\UpdateVtsProperty;

use Core\TwoStepForm\FormContextInterface;

class UpdateVtsContext implements FormContextInterface
{
    private $vtsId;
    private $propertyName;

    public function __construct($vtsId, $propertyName)
    {
        $this->vtsId = $vtsId;
        $this->propertyName = $propertyName;
    }

    public function getVtsId()
    {
        return $this->vtsId;
    }

    public function getPropertyName()
    {
        return $this->propertyName;
    }
}
