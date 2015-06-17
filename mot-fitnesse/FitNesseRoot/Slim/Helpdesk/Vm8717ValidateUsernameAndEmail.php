<?php

use MotFitnesse\Util\AccountUrlBuilder;
use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\TestShared;

class Helpdesk_Vm8717ValidateUsernameAndEmail
{
    protected $username;
    protected $response;

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function userValid()
    {
        $client = FitMotApiClient::createForCreds(
            new CredentialsProvider('csco', TestShared::PASSWORD)
        );

        $url = AccountUrlBuilder::validateUsername()
            ->queryParam('username', $this->username);

        try {
            $this->response = $client->get($url);

            if ($this->response === false) {
                return false;
            }
            return true;
        } catch (ApiErrorException $e) {
            return false;
        }
    }
}
