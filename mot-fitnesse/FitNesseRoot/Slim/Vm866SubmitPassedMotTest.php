<?php

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm866SubmitPassedMotTest
{

    private $testerId;

    private $testerUsername;

    public function setTesterId($testerId)
    {
        $this->testerId = $testerId;
    }

    public function setTesterUsername($testerUsername)
    {
        $this->testerUsername = $testerUsername;
    }


    public function slotsAfterTestComplete()
    {
        //Retrieve slot count again to reflect update.

        $jsonResult = TestShared::execCurlForJsonFromUrlBuilder(
            new \MotFitnesse\Util\CredentialsProvider($this->testerUsername, TestShared::PASSWORD),
            (new UrlBuilder())->tester()->routeParam('id', $this->testerId)
        );

        $newSlots = $jsonResult['data']['vtsSites'][0]['slots'];

        return $newSlots;
    }
}
