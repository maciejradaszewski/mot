<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotPurchase_OrderDetail
 *
 * Get order details from the invoice
 */
class SlotPurchase_OrderDetail
{

    private $apiResult;
    private $credential;
    private $transaction;
    private $aedm;
    private $organisation;

    public function execute()
    {
        $param            = [
            'organisation' => $this->organisation
        ];
        $this->credential = new CredentialsProvider($this->aedm, TestShared::PASSWORD);
        $endPoint         = SlotPurchaseUrlBuilder::of()->orderDetails($this->transaction);
        $endPoint->queryParams($param);
        $this->apiResult = TestShared::get($endPoint->toString(), $this->aedm, TestShared::PASSWORD);
    }

    /**
     * @param mixed $organisation
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * @param mixed $aedm
     */
    public function setAedm($aedm)
    {
        $this->aedm = $aedm;
    }

    public function setTransaction($id)
    {
        $this->transaction = $id;
    }

    public function productCode()
    {
        if (is_array($this->apiResult) and isset($this->apiResult['product_code'])) {
            return $this->apiResult['product_code'];
        }

        return 0;
    }

    public function date()
    {
        if (is_array($this->apiResult) and isset($this->apiResult['date'])) {
            return true;
        }

        return 0;
    }

    public function slotPriceVatExc()
    {
        if (is_array($this->apiResult) and isset($this->apiResult['slot_price_vat_exc'])) {
            return $this->apiResult['slot_price_vat_exc'];
        }

        return 0;
    }

    public function slotPriceVatInc()
    {
        if (is_array($this->apiResult) and isset($this->apiResult['slot_price_vat_inc'])) {
            return $this->apiResult['slot_price_vat_inc'];
        }

        return 0;
    }

    public function totalPrice()
    {
        if (is_array($this->apiResult) and isset($this->apiResult['total_price'])) {
            return true;
        }

        return 0;
    }

    public function salesReference()
    {
        if (is_array($this->apiResult) and isset($this->apiResult['sales_reference'])) {
            return true;
        }

        return 0;
    }

    public function description()
    {
        if (is_array($this->apiResult) and isset($this->apiResult['description'])) {
            return true;
        }

        return 0;
    }

    public function quantity()
    {
        if (is_array($this->apiResult) and isset($this->apiResult['quantity'])) {
            return true;
        }

        return 0;
    }

    public function vatRate()
    {
        if (is_array($this->apiResult) and isset($this->apiResult['vat_rate'])) {
            return $this->apiResult['vat_rate'];
        }

        return false;
    }
}
