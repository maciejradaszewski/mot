<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotPurchase_TransactionSearch
 */
class SlotPurchase_TransactionSearch
{

    private $apiResult;
    private $credential;
    private $reference;
    private $username;
    private $endPoint;

    public function execute()
    {
        $this->credential = new CredentialsProvider($this->username, TestShared::PASSWORD);
        $endPoint         = SlotPurchaseUrlBuilder::of()->transactionSearch($this->reference);
        $this->endPoint   = $endPoint->toString();
        $this->apiResult  = TestShared::get($endPoint->toString(), $this->username, TestShared::PASSWORD);
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setReference($id)
    {
        $this->reference = $id;
    }

    public function name()
    {
        if (isset($this->apiResult['name'])) {
            return true;
        }

        return 0;
    }

    public function aeName()
    {
        if (isset($this->apiResult['ae_name'])) {
            return true;
        }

        return 0;
    }

    public function transaction()
    {
        if (isset($this->apiResult['transaction_id'])) {
            return true;
        }

        return 0;
    }

    public function organisation()
    {
        if (isset($this->apiResult['organisation_id'])) {
            return true;
        }

        return 0;
    }

    public function found()
    {
        if (isset($this->apiResult['found'])) {
            return $this->apiResult['found'];
        }

        return 0;
    }
}
