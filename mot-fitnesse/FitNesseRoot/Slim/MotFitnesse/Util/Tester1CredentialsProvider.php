<?php
namespace MotFitnesse\Util;

class Tester1CredentialsProvider extends CredentialsProvider
{
    public function __construct()
    {
        parent::__construct(TestShared::USERNAME_TESTER1, TestShared::PASSWORD);
    }
}