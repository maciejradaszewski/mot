<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/*
 *
 */

class SlotPurchase_SlotUsageSite
{

    private $apiResult;
    private $credential;
    private $endPoint;
    private $siteId;

    const AEDM_USERNAME = 'aedm';

    public function execute()
    {
        $this->credential = new CredentialsProvider(self::AEDM_USERNAME, TestShared::PASSWORD);
        $endPoint         = SlotPurchaseUrlBuilder::of()->slotUsageSite($this->siteId);
        $endPoint->queryParams([]);
        $this->endPoint  = $endPoint->toString();
        $this->apiResult = TestShared::get($endPoint->toString(), self::AEDM_USERNAME, TestShared::PASSWORD);
    }

    public function setSite($id)
    {
        $this->siteId = $id;
    }

    public function totalRowsNumber()
    {
        if (isset($this->apiResult['total_rows_number'])) {
            return true;
        }

        return 0;
    }

    public function rowsNumber()
    {
        if (isset($this->apiResult['rows_number'])) {
            return true;
        }

        return print_r($this->apiResult, true);
    }

    public function rows()
    {
        if (isset($this->apiResult['rows'])) {
            return true;
        }

        return 0;
    }
}
