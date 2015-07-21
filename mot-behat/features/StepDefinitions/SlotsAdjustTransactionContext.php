<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Support\Api\Session;
use Dvsa\Mot\Behat\Support\Api\SlotPurchase;
use Dvsa\Mot\Behat\Support\Response;
use PHPUnit_Framework_Assert as PHPUnit;

class SlotsAdjustTransactionContext implements Context
{
    /**
     * @var SlotPurchase
     */
    private $slotPurchase;

    /**
     * @var SessionContext
     */
    private $sessionContext;
    /**
     * @var array
     */
    private $organisationMap = [
        'kwikfit'  => 10,
        'halfords' => 9,
        'asda'     => 12
    ];

    private $reasonMap = [
        'wrongData' => 'IV',

    ];
    /**
     * @var Response
     */
    private $responseReceived;

    /**
     * @param SlotPurchase $directDebit
     */
    public function __construct(SlotPurchase $slotPurchase)
    {
        $this->slotPurchase = $slotPurchase;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->sessionContext = $scope->getEnvironment()->getContext(SessionContext::class);
    }

    /**
     * @Given A slot transaction exist
     */
    public function aSlotTransactionExist()
    {
        $slots                  = rand(25, 500);
        $this->responseReceived = $this->slotPurchase->makePaymentForSlot(
            $this->sessionContext->getCurrentAccessToken(), $slots, $this->organisationMap['halfords']
        );
    }

    /**
     * @When I adjust the transaction to the correct Authorised Examiner :authorisedExaminer
     */
    public function iAdjustTheTransactionTotheCorrectAuthorisedExaminer($authorisedExaminer)
    {
        $data                   = $this->responseReceived->getBody();
        $transactionId          = $data['data']['transaction_id'];
        $token                  = $this->sessionContext->getCurrentAccessToken();
        $body                   = [
            'organisationId' => $this->organisationMap[$authorisedExaminer],
        ];
        $this->responseReceived = $this->slotPurchase->adjustTransaction($token, $transactionId, $body);
    }

    /**
     * @Then The transaction should be adjusted
     */
    public function theTransactionShouldBeCorrectlyAdjusted()
    {
        PHPUnit::assertEquals(
            200,
            $this->responseReceived->getStatusCode(),
            'Unable to adjust transaction'
        );
    }

    /**
     * @When I adjust the transaction attribute :field to :value because of :reason
     */
    public function iAdjustTheTransactionAttributeTo($field, $value, $reason)
    {
        $data                   = $this->responseReceived->getBody();
        $transactionId          = $data['data']['transaction_id'];
        $token                  = $this->sessionContext->getCurrentAccessToken();
        $body                   = [
            'statusCode' => $this->reasonMap[$reason],
            $field       => $value
        ];
        $this->responseReceived = $this->slotPurchase->adjustTransaction($token, $transactionId, $body);
    }
}
