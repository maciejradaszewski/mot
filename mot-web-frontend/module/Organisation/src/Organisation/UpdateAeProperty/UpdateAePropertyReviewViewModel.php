<?php

namespace Organisation\UpdateAeProperty;

use Core\ViewModel\Gds\Table\GdsTable;

class UpdateAePropertyReviewViewModel
{
    private $submitButtonText;
    private $formData;
    private $aeId;
    private $summary;
    private $propertyName;
    private $formUuid;

    public function __construct($aeId, $propertyName, $formUuid, $submitButtonText, $formData, GdsTable $summary)
    {
        $this->submitButtonText = $submitButtonText;
        $this->formData = $formData;
        $this->aeId = $aeId;
        $this->summary = $summary;
        $this->propertyName = $propertyName;
        $this->formUuid = $formUuid;
    }

    public function getSubmitButtonText()
    {
        return $this->submitButtonText;
    }

    public function getFormData()
    {
        return $this->formData;
    }

    public function getAeId()
    {
        return $this->aeId;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function getPropertyName()
    {
        return $this->propertyName;
    }

    public function getFormUuid()
    {
        return $this->formUuid;
    }
}
