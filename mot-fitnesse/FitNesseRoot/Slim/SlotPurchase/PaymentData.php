<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotConfiguration
 *
 * Get the data required for make payment request to CPMS
 *
 * @package MotFitnesse\SlotPurchase
 */
class SlotPurchase_PaymentData
{

    private $organisationId;
    private $apiResult;
    private $credential;
    private $aedm;

    public function execute()
    {
        $this->credential = new CredentialsProvider($this->aedm, TestShared::PASSWORD);
        $endPoint         = SlotPurchaseUrlBuilder::of()->paymentData($this->organisationId);
        $this->apiResult  = TestShared::get($endPoint->toString(), $this->aedm, TestShared::PASSWORD);
    }

    /**
     * @param mixed $aedm
     */
    public function setAedm($aedm)
    {
        $this->aedm = $aedm;
    }

    public function setOrganisationId($id)
    {
        $this->organisationId = $id;
    }

    public function costCentre()
    {
        if (isset($this->apiResult['cost_centre'])) {
            return $this->apiResult['cost_centre'];
        }

        return 0;
    }

    public function productReference()
    {
        if (isset($this->apiResult['product_reference'])) {
            return $this->apiResult['product_reference'];
        }

        return 0;
    }

    public function customerReference()
    {
        if (isset($this->apiResult['customer_reference'])) {
            return 'MOT';
        }

        return 0;
    }

    public function userId()
    {
        if (isset($this->apiResult['user_id'])) {
            return 12345;
        }

        return 0;
    }
}
