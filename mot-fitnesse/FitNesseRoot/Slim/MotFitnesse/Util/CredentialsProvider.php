<?php

namespace MotFitnesse\Util;

/**
 * provids simple object you can use to authorise API call
 */
class CredentialsProvider
{
    public $username;
    public $password;

    public function __construct($username, $password = TestShared::PASSWORD)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public static function fromArray($data)
    {
        return new CredentialsProvider($data['username'], $data['password']);
    }
}
