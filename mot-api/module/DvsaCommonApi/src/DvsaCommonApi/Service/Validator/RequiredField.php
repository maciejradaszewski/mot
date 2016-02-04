<?php

namespace DvsaCommonApi\Service\Validator;

class RequiredField
{
    private $dataName;
    private $displayName;

    public function __construct($dataName, $displayName)
    {
        $this->dataName = $dataName;
        $this->displayName = $displayName;
    }

    public function getDataName()
    {
        return $this->dataName;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }
}
