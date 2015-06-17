<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

/**
 * Checks response after posting to api to nominate a site role
 */
class Vm2329NominateSiteRole
{
    private $username = TestShared::USERNAME_ENFORCEMENT;
    private $password = TestShared::PASSWORD;

    private $siteId;
    private $nomineeId;
    private $roleCode;

    private $result;

    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

    public function setNomineeId($nomineeId)
    {
        $this->nomineeId = $nomineeId;
    }

    public function setRoleName($role)
    {
        $this->roleCode = $role;
    }

    private function input()
    {
        $postFields = [
            'nomineeId' => $this->nomineeId,
            'roleCode'  => $this->roleCode,
        ];
        return $postFields;
    }

    public function success()
    {
        $url = UrlBuilder::sitePosition($this->siteId)->toString();

        $postFields = $this->input();

        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            $url,
            TestShared::METHOD_POST,
            $postFields,
            $this->username,
            $this->password
        );

        $this->result = TestShared::execCurlForJson($curlHandle);

        return TestShared::resultIsSuccess($this->result);
    }

    public function errorMessages()
    {
        return TestShared::errorMessages($this->result);
    }
}
