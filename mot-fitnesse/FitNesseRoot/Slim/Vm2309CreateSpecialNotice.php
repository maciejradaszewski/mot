<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm2309CreateSpecialNotice
{

    public $username = TestShared::USERNAME_SCHEMEUSER;
    public $password = TestShared::PASSWORD;

    private $targetRoles = [];
    private $input = [];
    private $error = null;
    private $isPublished = null;
    private $editPrevious = false;
    private $lastCreatedId = null;
    private $publish = false;
    private $wrongDate = null;

    private function execute()
    {

        $httpVerb = $this->editPrevious ? TestShared::METHOD_PUT : TestShared::METHOD_POST;

        $url = $this->editPrevious
            ? (new UrlBuilder())->specialNoticeContent()->routeParam('id', $this->lastCreatedId)
            : (new UrlBuilder())->specialNoticeCreate();

        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            $url->toString(),
            $httpVerb,
            $this->input,
            $this->username,
            $this->password
        );

        $response = TestShared::execCurlForJson($curlHandle);

        if ($this->publish && array_key_exists('data', $response)) {
            $curlHandle = TestShared::prepareCurlHandleToSendJson(
                (new UrlBuilder())->specialNoticePublish()->routeParam('id', $response['data']['id'])->toString(),
                TestShared::METHOD_PUT,
                [],
                $this->username,
                $this->password
            );
            $response = TestShared::execCurlForJson($curlHandle);
        }

        return $response;
    }

    public function result()
    {
        $result = $this->execute();

        if (array_key_exists('errors', $result)) {
            $this->error = $result['errors'][0];
            return $this->error['displayMessage'];
        } else {
            $this->error = null;
        }

        $specialNotice = $result['data'];
        if ($specialNotice) {
            $this->isPublished = $specialNotice['isPublished'];
            $this->lastCreatedId = $specialNotice['id'];
            return 'Special Notice Saved';
        } else {
            return 'There was a problem';
        }
    }

    public function published()
    {
        $value = $this->isPublished === null ? "N/A" : ($this->isPublished == 1 ? "Yes" : "No");
        $this->reset();
        return $value;
    }

    private function reset()
    {
        $this->targetRoles = [];
        $this->input = [];
        $this->error = null;
        $this->isPublished = null;
        $this->editPrevious = false;
    }

    private function setInputValue($name, $value)
    {
        if (!empty($value)) {
            $this->input[$name] = $value;
        }
    }

    public function setEditPrevious($value)
    {
        $this->editPrevious = strtoupper($value) === 'YES';
    }

    public function setPublish($value)
    {
        $this->publish = strtoupper($value) === 'YES';
    }

    public function setAcknowledgementPeriod($acknowledgementPeriod)
    {
        $this->setInputValue('acknowledgementPeriod', $acknowledgementPeriod);
    }

    public function setExternalPublishDate($externalPublishDate)
    {
        $this->setInputValue('externalPublishDate', $externalPublishDate);
    }

    public function setInternalPublishDate($internalPublishDate)
    {
        $this->setInputValue('internalPublishDate', $internalPublishDate);
    }

    public function setSubjectMessage($subjectMessage)
    {
        $this->setInputValue('noticeText', $subjectMessage);
    }

    public function setTargetRoles($targetRoles)
    {
        $this->targetRoles = ($targetRoles !== "") ?  explode(" ", $targetRoles) : [];
        $this->setInputValue('targetRoles', $this->targetRoles);
    }

    public function setSubjectTitle($title)
    {
        $this->setInputValue('noticeTitle', $title);
    }

    public function setInfoAboutResult()
    {
    }

    public function setWrongDate() {
        $now = new DateTime();
        //setting date to next year to pass api publish date validation
        $year = ($now->format("Y") + 1);
        $this->wrongDate = $year."-02-31";
        $this->setInputValue('wrongDate', $this->wrongDate);
    }
}
