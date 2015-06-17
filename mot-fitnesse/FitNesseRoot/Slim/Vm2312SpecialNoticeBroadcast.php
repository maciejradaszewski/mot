<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm2312SpecialNoticeBroadcast
{
    private $jobUserName = 'cron-job';
    private $jobPassword = TestShared::PASSWORD;

    private $curlUserName = 'tester1';
    private $curlPassword = TestShared::PASSWORD;

    private $userName = 'a';
    private $personId;
    private $specialNoticeContentTitle = 'a';

    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    public function setPersonId($personId)
    {
        $this->personId = $personId;
    }

    public function setSpecialNoticeContentTitle($specialNoticeContentTitle)
    {
        $this->specialNoticeContentTitle = $specialNoticeContentTitle;
    }

    private function doBroadcast()
    {
        $postData = ['username' => $this->jobUserName, 'password' => $this->jobPassword];

        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            (new UrlBuilder())->specialNoticeBroadcast()->toString(),
            TestShared::METHOD_POST,
            $postData,
            $this->jobUserName,
            $this->jobPassword
        );

        return TestShared::execCurlForJson($curlHandle);
    }

    private function getSpecialNoticesForUserQuery()
    {
        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            (new UrlBuilder())->specialNotice()
                              ->routeParam('id', $this->personId)
                              ->toString(),
            TestShared::METHOD_GET,
            null,
            $this->curlUserName,
            $this->curlPassword
        );

        return TestShared::execCurlForJson($curlHandle);
    }

    public function isSpecialNoticeAdded()
    {
        $this->doBroadcast();
        $result = $this->getSpecialNoticesForUserQuery();
        $specialNotices = $result['data'];

        foreach ($specialNotices as $specialNotice) {
            if ($specialNotice['content']['title'] == $this->specialNoticeContentTitle) {
                return true;
            }
        }

        return false;
    }
}
