<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotPurchase_InstantSettlement
 */
class SlotPurchase_InstantSettlement
{

    private $organisation;
    private $apiResult;
    private $paymentType;
    private $amount;
    private $chequeDate;
    private $aedm;
    private $slipNumber;
    private $chequeNumber;
    private $accountName;
    private $costCentre;
    private $customerReference;
    private $productReference;
    private $userId;
    private $paidAmount;
    private $slots;
    private $autoRefund;

    private $data;

    public function execute()
    {
        $data            = [
            'organisation' => $this->organisation,
            'paidAmount'   => $this->amount,
            'amount'       => $this->paidAmount,
            'slots'        => $this->slots,
            'paymentType'  => $this->paymentType,
            'autoRefund'   => $this->autoRefund,
            'paymentData'  => [
                'chequeDate'   => (new \DateTime($this->chequeDate))->format('Y-m-d'),
                'slipNumber'   => $this->slipNumber,
                'chequeNumber' => $this->chequeNumber,
                'accountName'  => $this->accountName
            ]
        ];
        $this->data      = $data;
        $credentials     = new CredentialsProvider($this->aedm, TestShared::PASSWORD);
        $endPoint        = SlotPurchaseUrlBuilder::of()->settlePayment();
        $this->apiResult = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            $credentials,
            $endPoint,
            $data
        );
    }

    /**
     * @param mixed $autoRefund
     */
    public function setAutoRefund($autoRefund)
    {
        $this->autoRefund = $autoRefund;
    }

    /**
     * @param mixed $slots
     */
    public function setSlots($slots)
    {
        $this->slots = $slots;
    }

    /**
     * @param mixed $paidAmount
     */
    public function setPaidAmount($paidAmount)
    {
        $this->paidAmount = $paidAmount;
    }

    /**
     * @param mixed $slipNumber
     */
    public function setSlipNumber($slipNumber)
    {
        $this->slipNumber = $slipNumber;
    }

    /**
     * @param mixed $chequeNumber
     */
    public function setChequeNumber($chequeNumber)
    {
        $this->chequeNumber = $chequeNumber;
    }

    /**
     * @param mixed $accountName
     */
    public function setAccountName($accountName)
    {
        $this->accountName = $accountName;
    }

    /**
     * @param mixed $costCentre
     */
    public function setCostCentre($costCentre)
    {
        $this->costCentre = $costCentre;
    }

    /**
     * @param mixed $customerReference
     */
    public function setCustomerReference($customerReference)
    {
        $this->customerReference = $customerReference;
    }

    /**
     * @param mixed $productReference
     */
    public function setProductReference($productReference)
    {
        $this->productReference = $productReference;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
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
     * @param int $chequeDate
     */
    public function setChequeDate($chequeDate)
    {
        $this->chequeDate = $chequeDate;
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

    public function transactionId()
    {
        if (isset($this->apiResult['data']['transaction_id'])) {
            return true;
        }

        return 0;
    }

    public function refundAmount()
    {
        if (isset($this->apiResult['data']['refund_amount'])) {
            return $this->apiResult['data']['refund_amount'];
        }

        return false;
    }

    public function errorCode()
    {

        if (isset($this->apiResult['errors']['code'])) {

            return $this->apiResult['errors']['code'];
        }

        if (isset($this->apiResult['validationError']['code'])) {

            return $this->apiResult['validationError']['code'];
        }

        if (isset($this->apiResult['errors'])) {
            $details = current($this->apiResult['errors']);

            return $details['code'];
        }

        return 0;
    }
}
