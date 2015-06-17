<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotPurchase_PurchaserDetail
 *
 * Get details of the AE
 */
class SlotPurchase_PurchaserDetail
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
        $endPoint         = SlotPurchaseUrlBuilder::of()->purchaserDetails($this->transaction);
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

    public function name()
    {
        if (is_array($this->apiResult) and isset($this->apiResult['name'])) {
            return true;
        }

        return 0;
    }

    public function aeName()
    {
        if (is_array($this->apiResult) and isset($this->apiResult['ae_name'])) {
            return true;
        }

        return 0;
    }

    public function address()
    {
        if (is_array($this->apiResult) and isset($this->apiResult['address'])) {
            return true;
        }

        return 0;
    }

    public function aeNumber()
    {
        if (is_array($this->apiResult) and isset($this->apiResult['ae_number'])) {
            return true;
        }

        return 0;
    }
}
