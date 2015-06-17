<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotPurchase_AmountValidation
 *
 * Validate the amount and number of slots
 */
class SlotPurchase_AmountValidation
{

    private $organisation;
    private $apiResult;
    private $amount;
    private $slots;
    private $aedm;
    private $state;

    public function execute()
    {
        $credentials     = new CredentialsProvider($this->aedm, TestShared::PASSWORD);
        $data            = [
            'organisation' => $this->organisation,
            'slots'        => $this->slots,
            'amount'       => $this->amount
        ];
        $endPoint        = SlotPurchaseUrlBuilder::of()->validate();
        $this->apiResult = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            $credentials,
            $endPoint,
            $data
        );
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

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function setSlots($num)
    {
        $this->slots = $num;
    }

    public function orgReturned()
    {
        if (isset($this->apiResult['data'])) {
            return $this->apiResult['data']['organisation'];
        }

        return 0;
    }

    public function amountReturned()
    {
        if (isset($this->apiResult['data'])) {
            return $this->apiResult['data']['amount'];
        }

        return 0;
    }

    public function slotReturned()
    {
        if (isset($this->apiResult['data'])) {
            return $this->apiResult['data']['slots'];
        }

        return 0;
    }

    public function errorCode()
    {
        if (isset($this->apiResult['errors'])) {
            return $this->apiResult['errors']['code'];
        }

        return 0;
    }
}
