<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotPurchase_SiteSlotUsageNumber
 *
 * Return the slot usage number for a given site
 */
class SlotPurchase_SiteSlotUsageNumber
{
    private $apiResult;
    private $credential;
    private $aedm;
    private $siteId;
    private $organisation;

    public function execute()
    {
        $param            = [
            'organisation' => $this->organisation,
            'site'         => $this->siteId
        ];
        $this->credential = new CredentialsProvider($this->aedm, TestShared::PASSWORD);
        $endPoint         = SlotPurchaseUrlBuilder::of()->siteSlotUsageNumber($this->siteId);
        $this->apiResult  = TestShared::get($endPoint->queryParams($param)->toString(), $this->aedm, TestShared::PASSWORD);
    }

    /**
     * @param mixed $organisation
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * @param mixed $siteId
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
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
