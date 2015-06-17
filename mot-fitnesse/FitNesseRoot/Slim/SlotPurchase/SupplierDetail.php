<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotPurchase_SupplierDetail
 *
 * Get MOT Supplier Details
 */
class SlotPurchase_SupplierDetail
{

    private $apiResult;
    private $credential;
    private $endPoint;
    private $aedm;

    public function execute()
    {
        $this->credential = new CredentialsProvider($this->aedm, TestShared::PASSWORD);
        $endPoint         = SlotPurchaseUrlBuilder::of()->supplierDetails();
        $this->endPoint   = $endPoint->toString();
        $this->apiResult  = TestShared::get($endPoint->toString(), $this->aedm, TestShared::PASSWORD);
    }

    /**
     * @param mixed $aedm
     */
    public function setAedm($aedm)
    {
        $this->aedm = $aedm;
    }

    public function vatNumber()
    {
        if (isset($this->apiResult['vat_number'])) {
            return $this->apiResult['vat_number'];
        }

        return 0;
    }

    public function address()
    {
        if (isset($this->apiResult['address'])) {
            return $this->apiResult['address'];
        }

        return $this->endPoint;
    }

    public function name()
    {
        if (isset($this->apiResult['name'])) {
            return $this->apiResult['name'];
        }

        return 0;
    }
}
