<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotConfiguration
 *
 * Get defined slot purchase options e.g. price, min amount etc
 *
 * @package MotFitnesse\SlotPurchase
 */
class SlotPurchase_SlotUsageNumber
{

    private $organisation;
    private $apiResult;
    private $credential;
    private $aedm;
    private $siteId;

    public function execute()
    {
        $param            = [
            'organisation' => $this->organisation,
            'site'         => $this->siteId
        ];
        $this->credential = new CredentialsProvider($this->aedm, TestShared::PASSWORD);
        $endPoint         = SlotPurchaseUrlBuilder::of()->slotUsageNumber();
        $endPoint->queryParams($param);
        $this->apiResult = TestShared::get($endPoint->toString(), $this->aedm, TestShared::PASSWORD);
    }

    /**
     * @param mixed $site
     */
    public function setSiteId($site)
    {
        $this->siteId = $site;
    }

    /**
     * @param int $organisation
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

    public function slotUsageNumber()
    {
        if (is_array($this->apiResult) and array_key_exists('slot_usage_number', $this->apiResult)) {
            return true;
        }

        return 0;
    }
}
