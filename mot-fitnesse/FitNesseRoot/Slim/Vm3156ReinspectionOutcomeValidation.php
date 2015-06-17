<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm3156ReinspectionOutcomeValidation
{

    protected $result;

    public function setOutcome($value)
    {
        $postData = [];

        if ($value) {
            $postData['reinspection-outcome'] = $value;
        }

        $urlBuilder = (new UrlBuilder())->reinspectionOutcome();

        $curl = TestShared::prepareCurlHandleToSendJsonWithCreds(
            $urlBuilder->toString(),
            TestShared::METHOD_POST,
            $postData,
            new \MotFitnesse\Util\FtEnfTesterCredentialsProvider()
        );

        $this->result = TestShared::executeAndReturnStatusCodeWithAnyErrors($curl);

        return true;
    }

    public function response()
    {
        return (int)$this->result;
    }
}