<?php

use Behat\Behat\Context\Context;
use Dvsa\Mot\Behat\Support\Api\Session;
use Dvsa\Mot\Behat\Support\Api\SlotPurchase;
use Dvsa\Mot\Behat\Support\Response;
use Dvsa\Mot\Behat\Support\Data\UserData;
use PHPUnit_Framework_Assert as PHPUnit;

class SlotsRefreshPaymentsContext implements Context
{
    protected $slotPurchase;
    private $userData;

     /**
     * @var Response
     */
    protected $responseReceived;

    /**
     * @var Response
     */
    protected $paymentDetails;

    /**
     * @param SlotPurchase $slotPurchase
     */
    public function __construct(SlotPurchase $slotPurchase, UserData $userData)
    {
        $this->slotPurchase = $slotPurchase;
        $this->userData = $userData;
    }

    /**
     * @Given /^I have payment with status (.*) and is (.*) minutes old$/
     */
    public function iHavePaymentWithStatusAndIsMinutesOld($status, $minutes)
    {
        $this->responseReceived = $this->slotPurchase->makePaymentForSlot(
            $this->userData->getCurrentLoggedUser()->getAccessToken(),
            100,
            10
        );

        $transactionId        = $this->responseReceived->getBody()->getData()['transaction_id'];
        $this->paymentDetails = $this->slotPurchase->getPaymentDetails(
            $this->userData->getCurrentLoggedUser()->getAccessToken(),
            $transactionId
        )->getBody()->toArray();
    }

    /**
     * @When I attempt to refresh the payment status
     */
    public function iAttemptToRefreshThePaymentStatus()
    {
        $receiptReference = $this->paymentDetails['data']['receipt_reference'];

        $this->responseReceived = $this->slotPurchase->refreshPayment(
            $this->userData->getCurrentLoggedUser()->getAccessToken(),
            $receiptReference
        );
    }

    /**
     * @Then I should get valid message from refresh endpoint
     */
    public function iShouldGetValidMessageFromRefreshEndpoint()
    {
        PHPUnit::assertArrayHasKey('data', $this->responseReceived->getBody()->toArray());
        PHPUnit::assertArrayHasKey('message', $this->responseReceived->getBody()->getData());
    }
}
