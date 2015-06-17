<?php

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\TestShared;

class Helpdesk_Vm9332GenerateClaimAccountResetEmail
{
    protected $testerUserId;
    protected $response;
    protected $adminUsername;

    public function __construct($adminUsername)
    {
        $this->adminUsername = $adminUsername;
    }

    public function setTesterUserId($testerUserId)
    {
        $this->testerUserId = $testerUserId;
    }

    public function accountReset()
    {
        $client = FitMotApiClient::createForCreds(
            new CredentialsProvider($this->adminUsername, TestShared::PASSWORD)
        );

        $url = \MotFitnesse\Util\PersonUrlBuilder::resetClaimAccount($this->testerUserId);

        try {
            return $client->get($url);
        } catch (\Exception $e) {
            return false;
        }
    }
}
