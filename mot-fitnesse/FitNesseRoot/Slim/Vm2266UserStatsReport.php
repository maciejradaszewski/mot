<?php

require_once 'configure_autoload.php';
use MotFitnesse\Util\DashboardUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class Vm2266UserStatsReport
 */
class Vm2266UserStatsReport
{
    private $userId;
    private $totalToday;
    private $passesToday;
    private $failsToday;
    private $retestsToday;
    private $averageTestTime;
    private $failRate;

    public function success()
    {
        $curlHandle = $this->prepareCurlHandle();

        $result = TestShared::execCurlForJson($curlHandle);
        $data = $result['data'];
        $this->totalToday = $data['total'];
        $this->passesToday = $data['numberOfPasses'];
        $this->failsToday = $data['numberOfFails'];
        $this->retestsToday = $data['numberOfRetests'];
        $this->averageTestTime = $data['averageTime'];
        $this->failRate = round($data['failRate'], 2);

        return TestShared::resultIsSuccess($result);
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function totalToday()
    {
        return $this->totalToday;
    }

    public function passesToday()
    {
        return $this->passesToday;
    }

    public function failsToday()
    {
        return $this->failsToday;
    }

    public function retestsToday()
    {
        return $this->retestsToday;
    }

    public function averageTestTime()
    {
        return $this->averageTestTime;
    }

    public function failRate()
    {
        return $this->failRate;
    }

    private function prepareCurlHandle()
    {
        $apiUrl = DashboardUrlBuilder::userStats($this->userId)->toString();

        return TestShared::prepareCurlHandleToSendJson(
            $apiUrl,
            TestShared::METHOD_GET,
            null,
            'tester-dashboard-rpt',
            TestShared::PASSWORD
        );
    }
}
