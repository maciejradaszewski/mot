<?php

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm8078UserSiteCount
{
    private $userId;
    private $username;
    private $user;

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
    public function setUsername($username)
    {
        $this->username = $username;
    }

    private function prepareCurlHandle()
    {
        $url = (new UrlBuilder())->personGetSiteCount()->routeParam('id', $this->userId)->toString();

        return TestShared::prepareCurlHandleToSendJson($url, TestShared::METHOD_GET, null, $this->username, TestShared::PASSWORD);
    }

    public function setCreateUser()
    {
        if ($this->user === null) {
            $testSupport = new TestSupportHelper();
            $this->user = $testSupport->createUser();
        }
        return $this->user;
    }

    public function userUsername()
    {
        return $this->getUsername($this->setCreateUser());
    }

    public function userUserId()
    {
        return $this->getUserId($this->setCreateUser());
    }

    private function getUserId(array $person)
    {
        return $person['personId'];
    }

    private function getUsername(array $person)
    {
        return $person['username'];
    }

    public function siteCount()
    {
        $curlHandle = $this->prepareCurlHandle();

        $this->result = TestShared::execCurlForJson($curlHandle);
        return $this->result['data']['siteCount'];
    }
}
