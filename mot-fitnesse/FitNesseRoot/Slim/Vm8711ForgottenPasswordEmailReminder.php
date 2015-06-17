<?php

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\CredentialsProvider;

class Vm8711ForgottenPasswordEmailReminder
{
    protected $userId;
    protected $username = 'csco';
    protected $result;
    protected $error;


    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function reminderSent()
    {
        $this->result = null;
        $urlBuilder = (new UrlBuilder())->resetPassword();

        $client = FitMotApiClient::createForCreds(
            new CredentialsProvider(
                $this->username,
                TestShared::PASSWORD
            )
        );

        // The JSON payload for a mail reminder...
        $data = ['userId' => $this->userId];
        $this->error = 'no-error';

        try {
            $this->result = $client->post($urlBuilder, $data);
        } catch (ApiErrorException $e) {
            $this->error = $e->getMessage();
            return false;
        }
        return true;
    }

    public function errorMessage()
    {
        return $this->error;
    }
}
