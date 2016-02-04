<?php

namespace Organisation\UpdateAeProperty;

use Zend\Form\Form;

class UpdateAePropertyViewModel
{
    private $partial;
    private $submitButtonText;
    private $form;
    private $aeId;
    private $propertyName;

    public function __construct($aeId, $propertyName, $partial, $submitButtonText, Form $form)
    {
        $this->partial = $partial;
        $this->submitButtonText = $submitButtonText;
        $this->form = $form;
        $this->aeId = $aeId;
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
        return $this->aeId;
    }

    public function getPropertyName()
    {
        return $this->propertyName;
    }
}
