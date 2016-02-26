<?php

namespace DvsaAuthentication\Login\Response;

/**
 * Base class for all authentication responses
 */
abstract class AuthenticationResponse
{
    private $extra;

    public abstract function getCode();

    public abstract function getMessage();

    public function getExtra()
    {
        return $this->extra;
    }

    protected function setExtra(array $extra)
    {
        $this->extra = $extra;
    }
}