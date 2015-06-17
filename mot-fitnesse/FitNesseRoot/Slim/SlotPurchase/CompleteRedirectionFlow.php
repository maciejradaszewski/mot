<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotPurchase_CompleteRedirectionFlow
 *
 * Complete redirection flow  - make request to CPMS
 */
class SlotPurchase_CompleteRedirectionFlow
{

    private $receiptReference;
    private $apiResult;
    private $aedm;

    public function execute()
    {

        $endPoint = SlotPurchaseUrlBuilder::of()->completeTransaction($this->receiptReference);
        $this->apiResult = TestShared::get($endPoint->toString(), $this->aedm, TestShared::PASSWORD);
    }

    /**
     * @param mixed $aedm
     */
    public function setAedm($aedm)
    {
        $this->aedm = $aedm;
    }

    /**
     * @param string $receiptReference
     */
    public function setReceiptReference($receiptReference)
    {
        $this->receiptReference = $receiptReference;
    }

    public function code()
    {
        if (isset($this->apiResult['code']) and in_array($this->apiResult['code'], [802, 810])) {
            return true;
        }

        return false;
    }
}
