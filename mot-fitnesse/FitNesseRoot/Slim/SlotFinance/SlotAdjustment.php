<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotFinance_Adjustment
 */
class SlotFinance_SlotAdjustment
{

    private $organisation;
    private $apiResult;
    private $type;
    private $slots;
    private $aedm;
    private $state;
    private $reason;

    public function execute()
    {
        $credentials     = new CredentialsProvider($this->aedm, TestShared::PASSWORD);
        $data            = [
            'slots'        => $this->slots,
            'reason'       => $this->reason,
            'type'         => $this->type,
            'organisation' => $this->organisation
        ];
        $endPoint        = SlotPurchaseUrlBuilder::of()->adjustment();
        $this->apiResult = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            $credentials,
            $endPoint,
            $data
        );
    }

    /**
     * @param mixed $reason
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @param mixed $aedm
     */
    public function setAedm($aedm)
    {
        $this->aedm = $aedm;
    }

    public function setOrganisation($id)
    {
        $this->organisation = $id;
    }

    public function setType($amount)
    {
        $this->type = $amount;
    }

    public function setSlots($num)
    {
        $this->slots = $num;
    }

    public function slotAdjusted()
    {
        if (isset($this->apiResult['data'])) {
            return $this->apiResult['data']['slots_adjusted'];
        }

        return 0;
    }

    public function slotBalance()
    {
        if (isset($this->apiResult['data']['slots_balance'])) {
            return true;
        }

        return false;
    }

    public function code()
    {
        if (isset($this->apiResult['validationError']['code'])) {
            return $this->apiResult['validationError']['code'];
        }

        return 0;
    }
}
