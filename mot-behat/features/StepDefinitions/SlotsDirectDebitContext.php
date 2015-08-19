<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Support\Api\DirectDebit;
use Dvsa\Mot\Behat\Support\Api\Session;
use Dvsa\Mot\Behat\Support\Response;
use PHPUnit_Framework_Assert as PHPUnit;

class SlotsDirectDebitContext implements Context
{
    /**
     * @var DirectDebit
     */
    private $directDebit;

    /**
     * @var SessionContext
     */
    private $sessionContext;
    /**
     * @var array
     */
    private $organisationMap = [
        'crazyWheels' => 10,
        'halfords'    => 9,
        'asda'        => 12,
        'city'        => 13,
        'speed'       => 1001,
        'kwikfit'     => 2001,
    ];

    private $slotPrice = 2.05;
    /**
     * @var Response
     */
    private $responseReceived;

    /**
     * @var  string
     */
    private $mandateReference;

    /**
     * @param DirectDebit $directDebit
     */
    public function __construct(DirectDebit $directDebit)
    {
        $this->directDebit = $directDebit;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->sessionContext = $scope->getEnvironment()->getContext(SessionContext::class);
    }

    /**
     * @Given I have an active direct debit mandate set up for :slots slots in :organisation on :day
     */
    public function iHaveAnActiveDirectDebitMandateSetUpFor($slots, $organisation, $day)
    {
        $token           = $this->sessionContext->getCurrentAccessToken();
        $mandateResponse = $this->directDebit->getActiveMandate($token, $this->organisationMap[$organisation]);
        $mandateBody     = $mandateResponse->getBody();

        if (empty($mandateBody['data']['mandate_id'])) {

            $this->responseReceived = $this->directDebit->setUpDirectDebitMandate(
                $token,
                $this->organisationMap[$organisation],
                $slots,
                $day,
                number_format($slots * $this->slotPrice, 2, '.', '')
            );

            PHPUnit::assertEquals(
                200,
                $this->responseReceived->getStatusCode(),
                'Unable to setup mandate'
            );
            PHPUnit::assertArrayNotHasKey(
                'validationError',
                $this->responseReceived->getBody()->toArray(),
                'Did not expect any errors while setting up a direct debit mandate'
            );
        }
    }

    /**
     * @Given I have an active direct debit mandate for :organisation
     */
    public function iHaveAnActiveDirectDebitMandate($organisation)
    {
        $token           = $this->sessionContext->getCurrentAccessToken();
        $mandateResponse = $this->directDebit->getActiveMandate($token, $this->organisationMap[$organisation]);
        $mandateBody     = $mandateResponse->getBody();

        if (isset($mandateBody['data']['mandate_id'])) {
            $this->mandateReference = $mandateBody['data']['mandate_id'];
        } else {
            throw new \Exception('No mandate found');
        }

        if ($mandateBody['data']['status']['code'] == 'C') {
            $this->directDebit->completeMandateSetup(
                $token, $this->organisationMap[$organisation], $mandateBody['data']['mandate_id']
            );
        }
    }

    /**
     * @When I request to cancel the direct debit for :organisation
     */
    public function iRequestToCancelTheDirectDebit($organisation)
    {
        $token                  = $this->sessionContext->getCurrentAccessToken();
        $this->responseReceived = $this->directDebit->cancelDirectDebit(
            $token,
            $this->organisationMap[$organisation],
            $this->mandateReference
        );
    }

    /**
     * @Then The direct debit should be inactive
     */
    public function theDirectDebitShouldBeInactive()
    {
        PHPUnit::assertEquals(
            200,
            $this->responseReceived->getStatusCode(),
            'Unable to cancel the direct debit'
        );

        $body = $this->responseReceived->getBody();

        PHPUnit::assertArrayHasKey('data', $body);
        PHPUnit::assertArrayHasKey('success', $body['data']);
    }

    /**
     * @Then My direct debit should not be canceled
     */
    public function myDirectDebitShouldNotBeCanceled()
    {
        $body = $this->responseReceived->getBody();
        PHPUnit::assertEquals(
            200,
            $this->responseReceived->getStatusCode(),
            'Direct request was not rejected'
        );

        PHPUnit::assertArrayHasKey('validationError', $body);
        PHPUnit::assertArrayHasKey('code', $body['validationError']);
    }

    /**
     * @When I setup direct debit of :numberOfSlots slots for :organisation on :dayOfMonth day of the month
     */
    public function iSetupDirectDebitOfSlotsForAsdaOnDayOfTheMonth($numberOfSlots, $organisation, $dayOfMonth)
    {
        $token           = $this->sessionContext->getCurrentAccessToken();
        $mandateResponse = $this->directDebit->getActiveMandate($token, $this->organisationMap[$organisation]);
        $mandateBody     = $mandateResponse->getBody();

        if (isset($mandateBody['data']['mandate_id'])) {
            //Cancel existing mandate
            $this->directDebit->cancelDirectDebit(
                $token,
                $this->organisationMap[$organisation],
                $mandateBody['data']['mandate_id']
            );
        }

        $this->responseReceived = $this->directDebit->setUpDirectDebitMandate(
            $token,
            $this->organisationMap[$organisation],
            $numberOfSlots,
            $dayOfMonth,
            number_format($numberOfSlots * $this->slotPrice, 2, '.', '')
        );
    }

    /**
     * @Then The direct debit should not be setup
     */
    public function theDirectDebitShouldNotBeSetup()
    {
        $responseBody = $this->responseReceived->getBody();
        PHPUnit::assertArrayHasKey('validationError', $responseBody);
        PHPUnit::assertArrayHasKey('code', $responseBody['validationError']);
        PHPUnit::assertSame(433, $responseBody['validationError']['code']);
    }
}
