<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

/**
 * Checks list of organisation (Authorised Examiner) roles returned by api
 */
class Vm4343NewTestIsAddedToList
{
    private $result;
    private $vtsId;

    public function setVtsId($vtsId)
    {
        $this->vtsId = $vtsId;
    }

    public function numberOfTestsInProgress()
    {
        $this->makeCallForTestsInProgressInVts();

        return count($this->result['data']);
    }

    public function startNewMotTest()
    {
        $credentialsProvider = new \MotFitnesse\Util\Tester1CredentialsProvider();
        $motTestHelper = new MotTestHelper($credentialsProvider);

        $motTestHelper->createMotTest(
            2011,
            null,
            $this->vtsId
        );

        return true;
    }

    private function prepareCurlHandle()
    {
        $url = (new UrlBuilder())->vehicleTestingStation()->routeParam('id', $this->vtsId)->vtsTestInProgress()
            ->toString();

        $username = TestShared::USERNAME_TESTER1;
        $password = TestShared::PASSWORD;

        return TestShared::prepareCurlHandleToSendJson($url, TestShared::METHOD_GET, null, $username, $password);
    }

    private function makeCallForTestsInProgressInVts()
    {
        $curlHandle = $this->prepareCurlHandle();

        $this->result = TestShared::execCurlForJson($curlHandle);

        return TestShared::resultIsSuccess($this->result);
    }
}
