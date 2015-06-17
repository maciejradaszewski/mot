<?php

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class IntegrationMotInfo
{
    private $testNumber;
    private $vrm;

    public function setTestNumber($value)
    {
        $this->testNumber = $value;
    }

    public function setVrm($value)
    {
        $this->vrm = $value;
    }

    public function found()
    {
        $queryParams = [];
        if ($this->testNumber) {
            $queryParams['testNumber'] = $this->testNumber;
        }
        if ($this->vrm) {
            $queryParams['vrm'] = $this->vrm;
        }

        $url = (new UrlBuilder())->integrationMotInfo()->queryParams($queryParams)->toString();

        try {
            $this->result = TestShared::get($url, 'tester1', TestShared::PASSWORD);
            return 'YES';
        } catch (Exception $e) {
            $this->result = $e->getMessage();
            return 'NO';
        }
    }

    public function result()
    {
        return $this->result;
    }

    public function setComment()
    {
    }
}
