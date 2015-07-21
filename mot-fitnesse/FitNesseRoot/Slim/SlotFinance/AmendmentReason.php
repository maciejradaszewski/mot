<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotFinance_AmendmentReason
 *
 * Retrieve the list of available amendment reasons
 */
class SlotFinance_AmendmentReason
{
    /** @var  array */
    private $apiResult;
    /** @var  CredentialsProvider */
    private $credential;
    /** @var  string */
    private $aedm;

    public function execute()
    {
        $this->credential = new CredentialsProvider($this->aedm, TestShared::PASSWORD);
        $endPoint         = SlotPurchaseUrlBuilder::of()->reason();
        $this->apiResult  = TestShared::get($endPoint->toString(), $this->aedm, TestShared::PASSWORD);
    }

    /**
     * @param string $aedm
     */
    public function setAedm($aedm)
    {
        $this->aedm = $aedm;
    }

    public function reasonsFound()
    {
        if (isset($this->apiResult)) {
            return count($this->apiResult) > 0;
        }

        return false;
    }

    public function errorCode()
    {
        if (isset($this->apiResult['validationError'])) {
            return $this->apiResult['validationError']['code'];
        }

        return 0;
    }
}
