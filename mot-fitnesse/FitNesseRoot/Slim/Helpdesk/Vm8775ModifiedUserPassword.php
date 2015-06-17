<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\CredentialsProvider;

/**
 * Class Helpdesk_Vm8775ModifiedUserPassword
 */
class Helpdesk_Vm8775ModifiedUserPassword
{
    protected $username = 'csco';

    /** @var  FitMotApiClient */
    private $client;

    private $userId;
    private $newPassword = '';
    private $tokenType;
    private $errorMessage;
    private $success;
    private $token;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }

    public function setTokenType($tokenType)
    {
        $this->tokenType = $tokenType;
    }

    public function beginTable()
    {
        $this->client = FitMotApiClient::createForCreds(
            new CredentialsProvider(
                $this->username,
                TestShared::PASSWORD
            )
        );
        $urlBuilder = (new UrlBuilder())->resetPassword();
        $data = ['userId' => $this->userId];
        $result = $this->client->post($urlBuilder, $data);
        $this->token = $result['token'];
    }

    private function getToken()
    {
        if ($this->tokenType === 'empty') {
            return '';
        } elseif ($this->tokenType === 'invalid') {
            return 'INVALIDTOKEN12';
        }

        return $this->token;
    }

    public function execute()
    {
        $encoder = new \DvsaCommon\Obfuscate\ParamEncoder();

        $data = [
            'token' => $this->getToken(),
            'newPassword' => $encoder->encode($this->newPassword),
        ];

        $urlBuilder = (new UrlBuilder())->changePassword();

        try {
            $this->client->post($urlBuilder, $data);
            $this->errorMessage = '';
            $this->success = true;
        } catch (ApiErrorException $e) {
            $this->errorMessage = $e->getMessage();
            $this->success = false;
        }
    }

    public function errorMessage()
    {
        if ($this->tokenType == 'expired') {
            return 'Token Expired';
        }
        return $this->errorMessage;
    }

    public function success()
    {
        return $this->success;
    }
}
