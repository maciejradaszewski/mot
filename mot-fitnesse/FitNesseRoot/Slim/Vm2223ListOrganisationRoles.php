<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

/**
 * Checks list of organisation (Authorised Examiner) roles returned by api
 */
class Vm2223ListOrganisationRoles
{
    const ROLE_DISPLAY_NAME_AEDM = 'Authorised Examiner Designated Manager';
    const ROLE_DISPLAY_NAME_AED = 'Authorised Examiner Delegate';

    private $result;
    private $organisationId = 9;
    private $personId = 1;
    private $username;

    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Only a verbal description of the test case.
     * Nothing functional.
     *
     * @param $description
     */
    public function setDescription($description)
    {

    }

    public function success()
    {
        $curlHandle = $this->prepareCurlHandle();

        $this->result = TestShared::execCurlForJson($curlHandle);

        return TestShared::resultIsSuccess($this->result);
    }

    private function prepareCurlHandle()
    {
        $url = UrlBuilder::organisationRoles($this->organisationId, $this->personId)->toString();

        return TestShared::prepareCurlHandleToSendJson($url, TestShared::METHOD_GET, null, $this->username,TestShared::PASSWORD);
    }

    public function aedmListed()
    {
        foreach ($this->result['data'] as $role)
        {
            if ($role == self::ROLE_DISPLAY_NAME_AEDM){
                return true;
            }
        }

        return false;
    }

    public function aedListed()
    {
        foreach ($this->result['data'] as $role)
        {
            if ($role == self::ROLE_DISPLAY_NAME_AED){
                return true;
            }
        }

        return false;
    }
}
