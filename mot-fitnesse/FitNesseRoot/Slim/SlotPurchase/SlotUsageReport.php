<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotPurchase_SlotUsageReport
 */
class SlotPurchase_SlotUsageReport
{

    protected $organisation;
    protected $apiResult;
    protected $credential;
    private $aedm;

    public function execute()
    {
        $param            = [
            'organisation' => $this->organisation
        ];
        $this->credential = new CredentialsProvider($this->aedm, TestShared::PASSWORD);
        $endPoint         = SlotPurchaseUrlBuilder::of()->slotUsageReport();
        $endPoint->queryParams($param);
        $this->apiResult = TestShared::get($endPoint->toString(), $this->aedm, TestShared::PASSWORD);
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

    public function totalRowsNumber()
    {
        if (array_key_exists('total_rows_number', $this->apiResult)) {
            return true;
        }

        return 0;
    }

    public function rowsNumber()
    {
        if (array_key_exists('rows_number', $this->apiResult)) {
            return true;
        }

        return 0;
    }

    public function rows()
    {
        if (array_key_exists('rows', $this->apiResult)) {
            return true;
        }

        return 0;
    }
}
