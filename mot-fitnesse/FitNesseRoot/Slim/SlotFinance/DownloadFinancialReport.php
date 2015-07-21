<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotFinance_DownloadFinancialReport
 */
class SlotFinance_DownloadFinancialReport
{
    /**
     * @var array
     */
    private $apiResult;
    /**
     * @var CredentialsProvider
     */
    private $credential;
    /**
     * @var string
     */
    private $user;
    /** @var  string */
    private $reference;

    public function execute()
    {
        $this->credential = new CredentialsProvider($this->user, TestShared::PASSWORD);
        $endPoint         = SlotPurchaseUrlBuilder::of()->downloadReport($this->reference);
        $this->apiResult  = TestShared::get($endPoint->toString(), $this->user, TestShared::PASSWORD);
    }

    /**
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return bool
     */
    public function fileExists()
    {
        if (isset($this->apiResult['file_stream'])) {
            return true;
        }

        return false;
    }

    /**
     * @return int
     */
    public function code()
    {
        if (isset($this->apiResult['validationError']['code'])) {
            return $this->apiResult['validationError']['code'];
        }

        return 0;
    }
}