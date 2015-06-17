<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotPurchase_TransactionHistory
 *
 * Return slots payment transaction history
 */
class SlotPurchase_TransactionHistory
{

    private $organisationId;
    private $apiResult;
    private $credential;
    private $endPoint;
    private $aedm;

    public function execute()
    {
        $this->credential = new CredentialsProvider($this->aedm, TestShared::PASSWORD);
        $endPoint         = SlotPurchaseUrlBuilder::of()->transactionHistory();
        $endPoint->queryParams(
            [
                'organisation' => $this->organisationId
            ]
        );
        $this->endPoint  = $endPoint->toString();
        $this->apiResult = TestShared::get($endPoint->toString(), $this->aedm, TestShared::PASSWORD);
    }

    /**
     * @param mixed $aedm
     */
    public function setAedm($aedm)
    {
        $this->aedm = $aedm;
    }

    public function setOrganisation($id)
    {
        $this->organisationId = $id;
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
