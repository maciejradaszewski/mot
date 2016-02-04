<?php

namespace Site\UpdateVtsProperty;

use Zend\Form\Form;

class UpdateVtsPropertyViewModel
{
    private $partial;
    private $submitButtonText;
    private $form;
    private $vtsId;
    private $propertyName;

    public function __construct($vtsId, $propertyName, $partial, $submitButtonText, Form $form)
    {
        $this->partial = $partial;
        $this->submitButtonText = $submitButtonText;
        $this->form = $form;
        $this->vtsId = $vtsId;
        $this->propertyName = $propertyName;
    }

    public function getPartial()
    {
        return $this->partial;
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function getAeId()
    {
        return $this->vtsId;
    }

    public function getPropertyName()
    {
        return $this->propertyName;
    }
}
