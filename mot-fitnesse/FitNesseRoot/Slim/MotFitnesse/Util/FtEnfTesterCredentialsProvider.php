<?php
/**
 * Created by PhpStorm.
 * User: arto
 * Date: 22/04/2014
 * Time: 16:33
 */

namespace MotFitnesse\Util;

class FtEnfTesterCredentialsProvider extends CredentialsProvider
{
    public function __construct()
    {
        parent::__construct('Ft-Enf-tester', TestShared::PASSWORD);
    }
}
