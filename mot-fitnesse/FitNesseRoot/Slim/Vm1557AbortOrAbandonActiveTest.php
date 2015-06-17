<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\AuthorisedExaminerUrlBuilder;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Vm1557AbortOrAbandonActiveTest
{
    private $motTestId;
    private $testStatus;
    private $reasonForCancelId;
    private $reasonForCancelNote;
    private $result;

    public $username = 'ae';
    public $password = TestShared::PASSWORD;

    public function setInfoAboutResult()
    {
    }

    public function setMotTestId($motTestId)
    {
        $this->motTestId = $motTestId;
    }

    public function setCurrentTestStatus($testStatus)
    {
        $this->testStatus = $testStatus;
    }

    public function setReasonForCancel()
    {
    }

    public function setReasonForCancelId($reasonForCancelId)
    {
        $this->reasonForCancelId = $reasonForCancelId;
    }

    public function setReasonForCancelNote($reasonForCancelNote)
    {
        $this->reasonForCancelNote = $reasonForCancelNote;
    }

    public function slotsBeforeCancel()
    {
        $ch = $this->prepareCurlHandleForGet();
        $jsonResult = TestShared::execCurlForJson($ch);
        $data = $jsonResult['data']['slots'];

        return $data;
    }

    public function success()
    {
        $data = [
            'status' => 'CANCEL',
            'reasonForCancel' => $this->reasonForCancelId,
            'cancelComment' => $this->reasonForCancelNote
        ];

        if (empty($this->reasonForCancelNote)) {
            unset($data['cancelComment']);
        }

        $urlBuilder = (new UrlBuilder())->motTest()->routeParam('id', $this->motTestId)->motTestStatus();

        $this->result = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            new \MotFitnesse\Util\Tester1CredentialsProvider(),
            $urlBuilder,
            $data
        );
        return TestShared::resultIsSuccess($this->result);
    }

    public function slotsAfterCancel()
    {
        $ch = $this->prepareCurlHandleForGet();
        $jsonResult = TestShared::execCurlForJson($ch);
        $data = $jsonResult['data']['slots'];

        return $data;
    }

    private function prepareCurlHandleForGet()
    {
        $url = AuthorisedExaminerUrlBuilder::authorisedExaminer($this->username)->toString();

        return TestShared::prepareCurlHandleToSendJson(
            $url,
            TestShared::METHOD_GET,
            null,
            $this->username,
            $this->password
        );
    }

    public function errorMessages()
    {
        return TestShared::errorMessages($this->result);
    }
}
