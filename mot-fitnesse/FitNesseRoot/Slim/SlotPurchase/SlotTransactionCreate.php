<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotPurchase_TransactionInitialise
 *
 * Initialise Test Slot Transaction by creating the transaction
 */
class SlotPurchase_SlotTransactionCreate
{

    private $organisation;
    private $apiResult;
    private $paymentType;
    private $amount;
    private $slots;
    private $aedm;
    public $password = TestShared::PASSWORD;

    public function execute()
    {
        $data = [
            'organisation' => $this->organisation,
            'slots'        => $this->slots,
            'amount'       => $this->amount,
            'paymentType'  => $this->paymentType
        ];

        $credentials     = new CredentialsProvider($this->aedm, TestShared::PASSWORD);
        $endPoint        = SlotPurchaseUrlBuilder::of()->initTransaction();
        $this->apiResult = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            $credentials,
            $endPoint,
            $data
        );
    }

    /**
     * @param mixed $aedm
     */
    public function setAedm($aedm)
    {
        $this->aedm = $aedm;
    }

    /**
     * @param int $organisation
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @param int $paymentType
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
    }

    /**
     * @param int $slots
     */
    public function setSlots($slots)
    {
        $this->slots = $slots;
    }

    public function state()
    {
        if (isset($this->apiResult['data']['state'])) {
            return true;
        }

        return 0;
    }

    public function salesReference()
    {
        if (isset($this->apiResult['data']['sales_reference'])) {
            return true;
        }

        return 0;
    }

    public function errorCode()
    {
        if (isset($this->apiResult['validationError'])) {
            return $this->apiResult['validationError']['code'];
        }

        return 0;
    }
}
