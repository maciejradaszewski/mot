<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotPurchase_MandateSetup
 */
class SlotPurchase_MandateSetup
{

    private $organisation;
    private $apiResult;
    private $collectionDay;
    private $redirectUri;
    private $slots;
    private $amount;
    private $aedm;

    public function execute()
    {
        $data = [
            'organisation'   => $this->organisation,
            'amount'         => $this->amount,
            'redirect_uri'   => $this->redirectUri,
            'slots'          => $this->slots,
            'collection_day' => $this->collectionDay,
        ];

        $credentials     = new CredentialsProvider($this->aedm, TestShared::PASSWORD);
        $endPoint        = SlotPurchaseUrlBuilder::of()->setUpMandate();
        $this->apiResult = TestShared::execCurlFormPostForJsonFromUrlBuilder($credentials, $endPoint, $data);
    }

    /**
     * @param mixed $collectionDay
     */
    public function setCollectionDay($collectionDay)
    {
        $this->collectionDay = $collectionDay;
    }

    /**
     * @param mixed $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * @param mixed $slots
     */
    public function setSlots($slots)
    {
        $this->slots = $slots;
    }

    /**
     * @param mixed $aedm
     */
    public function setAedm($aedm)
    {
        $this->aedm = $aedm;
    }

    /**
     * @param int $organisation
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function transactionId()
    {
        if (isset($this->apiResult['data']['id'])) {
            return true;
        }

        return false;
    }

    public function redirectUrl()
    {
        if (isset($this->apiResult['data']['redirect_url'])) {
            return true;
        }

        return false;
    }

    public function errorCode()
    {

        if (isset($this->apiResult['errors']['code'])) {

            return $this->apiResult['errors']['code'];
        }

        return 0;
    }
}
