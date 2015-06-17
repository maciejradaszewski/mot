<?php

require_once 'configure_autoload.php';
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Vm2151CertChangeDiffTesterReasons
{
    private $reason;
    public $username = 'tester1';
    public $password = TestShared::PASSWORD;

    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    public function present()
    {
        $reason = $this->reason;
        $results = (array)$this->doQuery()['data'];

        foreach ($results as $reasonResult) {
            if (in_array($reason, (array)$reasonResult)) {
                return true;
            }
        }

        return false;
    }

    protected function doQuery()
    {
        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            (new UrlBuilder())->certChangeDiffTesterReason()->toString(),
            TestShared::METHOD_GET,
            null,
            $this->username,
            $this->password
        );

        return TestShared::execCurlForJson($curlHandle);
    }
}
