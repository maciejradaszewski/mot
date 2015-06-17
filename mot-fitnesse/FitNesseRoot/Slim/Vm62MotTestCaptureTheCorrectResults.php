<?php

require_once 'configure_autoload.php';
use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm62MotTestCaptureTheCorrectResults
{
    private $tester;

    private $motTestNumber;

    private $result;

    public function __construct($tester){
        $this->tester = $tester;
    }

    public function setMotTestNumber($value)
    {
        $this->motTestNumber = $value;
    }

    public function setTester($tester){
        $this->tester;
    }

    public function success()
    {
        $urlBuilder = (new UrlBuilder())->motTest()->routeParam('motTestNumber', $this->motTestNumber);

        $this->result = TestShared::execCurlForJsonFromUrlBuilder(new CredentialsProvider($this->tester, TestShared::PASSWORD), $urlBuilder);
        return TestShared::resultIsSuccess($this->result);
    }

    public function setInfoAboutMotTest($value)
    {
    }

    public function errorMessages()
    {
        return TestShared::errorMessages($this->result);
    }
}
