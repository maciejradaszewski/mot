<?php

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Vm4498MotTestReadPermissionCheck
{

    /**
     * @var string
     */
    private $username;

    /**
     * @var bool
     */
    private $accessGranted;

    /**
     * @var string (numerical) 12 digits
     */
    private $motTestNumber;

    public function setWho($who)
    {
        $this->username = $who;
    }

    public function setMotTestNumber($motTestNumber)
    {
        $this->motTestNumber = $motTestNumber;
    }

    public function execute()
    {
        try {
            $apiClient = FitMotApiClient::createForCreds(
                new CredentialsProvider($this->username, TestShared::PASSWORD)
            );
            $apiClient->get(
                (new UrlBuilder())->motTest()->routeParam('motTestNumber', $this->motTestNumber)
            );
            $this->accessGranted = true;
        } catch (ApiErrorException $ex) {
            if ($ex->isForbiddenException()) {
                $this->accessGranted = false;
            } else {
                throw $ex;
            }
        }
    }

    public function hasAccess()
    {
        return $this->accessGranted;
    }

    public function setTestCaseDescription($description)
    {

    }
}
