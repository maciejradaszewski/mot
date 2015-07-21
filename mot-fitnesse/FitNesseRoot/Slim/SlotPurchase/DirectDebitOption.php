<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotPurchase_DirectDebitOption
 */
class SlotPurchase_DirectDebitOption
{

    private $apiResult;
    private $credential;
    private $aedm;
    private $organisation;

    public function execute()
    {
        $this->credential = new CredentialsProvider($this->aedm, TestShared::PASSWORD);
        $endPoint         = SlotPurchaseUrlBuilder::of()->directDebitOption($this->organisation);
        $this->apiResult  = TestShared::get($endPoint->toString(), $this->aedm, TestShared::PASSWORD);
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

    public function collectionDates()
    {
        if (isset($this->apiResult['directDebitCollectionDates'])) {
            if (is_array($this->apiResult['directDebitCollectionDates'])) {
                return true;
            }
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
