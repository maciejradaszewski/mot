<?php

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Vm8282SendReminderEmails
{
    protected $userId;
    protected $username;
    protected $result;

    public function asOperator($operator)
    {
        $this->username = $operator;
    }

    public function forUserPasswordReminder($userId)
    {
        return $this->sendReminder($userId, 'p');
    }

    public function forUserUsernameReminder($userId)
    {
        return $this->sendReminder($userId, 'u');
    }

    protected function sendReminder($userId, $type)
    {
        $urlBuilder = (new UrlBuilder())->genericMailer();

        $client = FitMotApiClient::createForCreds(
            new CredentialsProvider(
                $this->username,
                TestShared::PASSWORD
            )
        );

        // The JSON payload for a mail reminder...
        $data = [
            '_class' => '\\DvsaCommon\\Dto\\Mailer\\MailerDto',
            'type' => $type,
            'data' => [
                'userid' => $userId
            ]
        ];

        $result = false;

        try {
            $this->result = $client->post($urlBuilder, $data);

            if (isset($this->result['sent'])) {
                if ('yes' == $this->result['sent']
                    || 'inhibited' == $this->result['sent']
                ) {
                    $result = true;
                }
            }
        } catch (ApiErrorException $e) {
            $result = false;
        }
        return $result;
    }
}
