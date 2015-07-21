<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotFinance_CreateFinancialReport
 */
class SlotFinance_CreateFinancialReport
{
    /** @var  array */
    private $apiResult;
    /** @var  string */
    private $user;
    /** @var  string */
    private $dateFrom;
    /** @var  string */
    private $dateTo;
    /** @var  int */
    private $code;

    public function execute()
    {
        $data            = [
            'code'     => $this->code,
            'dateFrom' => (new DateTime($this->dateFrom))->format('Y-m-d'),
            'dateTo'   => (new DateTime($this->dateTo))->format('Y-m-d')
        ];
        $credentials     = new CredentialsProvider($this->user, TestShared::PASSWORD);
        $endPoint        = SlotPurchaseUrlBuilder::of()->createReport();
        $this->apiResult = TestShared::execCurlFormPostForJsonFromUrlBuilder($credentials, $endPoint, $data);
    }

    /**
     * @param string $dateFrom
     */
    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = $dateFrom;
    }

    /**
     * @param string $dateTo
     */
    public function setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return bool
     */
    public function report()
    {
        if (!empty($this->apiResult['data']['reference'])) {
            return true;
        }

        return false;
    }

    /**
     * @return int
     */
    public function errorCode()
    {
        if (isset($this->apiResult['validationError']['code'])) {
            return $this->apiResult['validationError']['code'];
        }

        return 0;
    }
}