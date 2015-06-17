<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotPurchase_SiteSlotUsageReport
 */
class SlotPurchase_SiteSlotUsageReport
{
    private $apiResult;
    private $credential;
    private $aedm;
    private $site;
    private $organisation;

    public function execute()
    {
        $param            = [
            'organisation' => $this->organisation,
            'site'         => $this->site
        ];
        $this->credential = new CredentialsProvider($this->aedm, TestShared::PASSWORD);
        $endPoint         = SlotPurchaseUrlBuilder::of()->slotUsageSite($this->site);
        $this->apiResult  = TestShared::get($endPoint->queryParams($param)->toString(), $this->aedm, TestShared::PASSWORD);
    }

    /**
     * @param mixed $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * @param mixed $organisation
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * @param mixed $site
     */
    public function setSiteId($site)
    {
        $this->site = $site;
    }

    /**
     * @param mixed $aedm
     */
    public function setAedm($aedm)
    {
        $this->aedm = $aedm;
    }

    public function totalRowsNumber()
    {
        if (is_array($this->apiResult) and array_key_exists('total_rows_number', $this->apiResult)) {
            return true;
        }

        return 0;
    }

    public function rowsNumber()
    {
        if (is_array($this->apiResult) and array_key_exists('rows_number', $this->apiResult)) {
            return true;
        }

        return 0;
    }

    public function rows()
    {
        if (is_array($this->apiResult) and array_key_exists('rows', $this->apiResult)) {
            return true;
        }

        return 0;
    }
}
