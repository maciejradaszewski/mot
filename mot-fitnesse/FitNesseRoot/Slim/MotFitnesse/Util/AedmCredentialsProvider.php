<?php
namespace MotFitnesse\Util;

/**
 * Fitness Credentials for AEDM
 */
class AedmCredentialsProvider extends CredentialsProvider
{
    public function __construct()
    {
        parent::__construct('aedm', TestShared::PASSWORD);
    }
}
