<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

class SlotFinance_ListFinancialReport
{

    private $apiResult;
    private $credential;
    private $user;

    public function execute()
    {
        $this->credential = new CredentialsProvider($this->user, TestShared::PASSWORD);
        $endPoint         = SlotPurchaseUrlBuilder::of()->listReport();
        $this->apiResult  = TestShared::get($endPoint->toString(), $this->user, TestShared::PASSWORD);
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function report()
    {
        if (is_array($this->apiResult)) {
            foreach ($this->apiResult as $report) {
                if (!isset($report['reference'])) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public function errorCode()
    {
        if (isset($this->apiResult['validationError'])) {
            return $this->apiResult['validationError']['code'];
        }

        return 0;
    }
}