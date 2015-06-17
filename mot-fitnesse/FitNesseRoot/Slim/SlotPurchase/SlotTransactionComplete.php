<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotPurchase_SlotTransactionComplete
 *
 * Complete the slot payment transaction and  update payment status
 */
class SlotPurchase_SlotTransactionComplete
{

    private $receiptReference;
    private $code;
    private $state;
    private $apiResult;
    private $organisation;
    private $aedm;

    public function execute()
    {
        $data                    = [
            'state'            => $this->state,
            'receiptReference' => $this->receiptReference,
            'code'             => $this->code,
            'organisation'     => $this->organisation
        ];

        $credentials     = new CredentialsProvider($this->aedm, TestShared::PASSWORD);
        $endPoint        = SlotPurchaseUrlBuilder::of()->completeTransaction($this->receiptReference);
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
     * @param string $organisation
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * @param string $receiptReference
     */
    public function setReceiptReference($receiptReference)
    {
        $this->receiptReference = $receiptReference;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    public function slotBalance()
    {
        if (isset($this->apiResult['data']['slotBalance'])) {
            return true;
        }

        return 0;
    }

    public function slots()
    {
        if (isset($this->apiResult['data']['slots'])) {
            return true;
        }

        return 0;
    }

    public function paymentType()
    {
        if (isset($this->apiResult['data']['slotBalance'])) {
            return true;
        }

        return 0;
    }

    public function amount()
    {
        if (isset($this->apiResult['data']['amount'])) {
            return true;
        }

        return 0;
    }

    public function transactionId()
    {
        if (isset($this->apiResult['data']['transactionId'])) {
            return true;
        }

        return 0;
    }

    public function errorCode()
    {
        if (isset($this->apiResult['errors']['code'])) {
            return $this->apiResult['errors']['code'];
        }

        return 0;
    }

    public function cancel()
    {
        if (isset($this->apiResult['data']['cancel'])) {
            return $this->apiResult['data']['cancel'];
        }

        return false;
    }

    public function notFound()
    {
        if (isset($this->apiResult['error'])) {
            return $this->apiResult['error'];
        }

        return 0;
    }
}
