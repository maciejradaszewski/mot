<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotConfiguration
 *
 * Get defined slot purchase options e.g. price, min amount etc
 *
 * @package MotFitnesse\SlotPurchase
 */
class SlotPurchase_SlotConfiguration
{

    private $apiResult;
    private $credential;
    private $aedm;

    public function execute()
    {
        $this->credential = new CredentialsProvider($this->aedm, TestShared::PASSWORD);
        $endPoint         = SlotPurchaseUrlBuilder::of()->slotConfiguration();
        $this->apiResult  = TestShared::get($endPoint->toString(), $this->aedm, TestShared::PASSWORD);
    }

    /**
     * @param mixed $aedm
     */
    public function setAedm($aedm)
    {
        $this->aedm = $aedm;
    }

    public function testSlotMinAmount()
    {
        if (isset($this->apiResult['testSlotMaxAmount'])) {
            return $this->apiResult['testSlotMinAmount'];
        }

        return 0;
    }

    public function testSlotMaxAmount()
    {
        if (isset($this->apiResult['testSlotMaxAmount'])) {
            return $this->apiResult['testSlotMaxAmount'];
        }

        return 0;
    }

    public function testSlotPrice()
    {
        if (isset($this->apiResult['testSlotPrice'])) {
            return $this->apiResult['testSlotPrice'];
        }

        return 0;
    }
}
