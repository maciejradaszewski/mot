<?php

use MotFitnesse\Util\TestShared;

class Vm12AndVm13Base
{
    public $username;
    protected $userId;
    public $password = TestShared::PASSWORD;

    public function setUserName($value)
    {
        $this->username = $value;
    }

    public function setUserId($value)
    {
        $this->userId = $value;
    }
}
