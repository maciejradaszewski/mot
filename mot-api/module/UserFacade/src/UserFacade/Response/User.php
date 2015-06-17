<?php

namespace UserFacade\Response;

/**
 * Class User
 */
class User
{
    private $uuid;
    private $roles;

    public function __construct($uuid, array $roles)
    {
        $this->uuid = $uuid;
        $this->roles = $roles;
    }
}
