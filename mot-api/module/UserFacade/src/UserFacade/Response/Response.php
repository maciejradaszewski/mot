<?php

namespace UserFacade\Response;

/**
 * Class Response
 */
class Response
{
    private $uuid;
    private $username;
    private $sessionToken;

    public function __construct($uuid, $username, $sessionToken)
    {
        $this->uuid = $uuid;
        $this->username = $username;
        $this->sessionToken = $sessionToken;
    }

    public function getSessionToken()
    {
        return $this->sessionToken;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getUuid()
    {
        return $this->uuid;
    }
}
