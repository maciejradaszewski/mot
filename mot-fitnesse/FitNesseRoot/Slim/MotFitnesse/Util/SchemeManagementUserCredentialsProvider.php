<?php
namespace MotFitnesse\Util;

class SchemeManagementUserCredentialsProvider extends CredentialsProvider
{
    public function __construct()
    {
        parent::__construct('schememgt', TestShared::PASSWORD);
    }
}
