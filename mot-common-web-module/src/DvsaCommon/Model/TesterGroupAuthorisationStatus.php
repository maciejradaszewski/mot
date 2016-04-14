<?php

namespace DvsaCommon\Model;

use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode;

class TesterGroupAuthorisationStatus
{
    private $code;
    private $name;

    public function __construct($statusCode, $name)
    {
        if ($statusCode && !AuthorisationForTestingMotStatusCode::exists($statusCode))
        {
            throw new \InvalidArgumentException("Value should come from 'AuthorisationForTestingMotStatusCode'");
        }

        $this->code = $statusCode;
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCode()
    {
        return $this->code;
    }
}
