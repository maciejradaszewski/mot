<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

/**
 * Checks list of site roles returned by api
 */
class Vm2552ListSiteRoles
{
    private $result;
    private $siteId = 1;
    private $personId = 1;
    private $role;

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function success()
    {
        $curlHandle = $this->prepareCurlHandle();

        $this->result = TestShared::execCurlForJson($curlHandle);

        return TestShared::resultIsSuccess($this->result);
    }

    private function prepareCurlHandle()
    {
        $url = UrlBuilder::siteRoles($this->siteId, $this->personId)->toString();

        return TestShared::prepareCurlHandleToSendJson($url, TestShared::METHOD_GET, null, 'tester1', TestShared::PASSWORD);
    }

    public function rolesReturned()
    {
        return $this->result['data'];
    }
}
