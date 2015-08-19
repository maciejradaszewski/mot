<?php

namespace DvsaAuthentication;

interface IdentityFactory
{
    /**
     * @param string $username
     * @param string $token
     * @param string $uuid
     *
     * @return Identity
     */
    public function create($username, $token, $uuid);
}