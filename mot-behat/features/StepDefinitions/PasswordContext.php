<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Support\Api\Session;
use Dvsa\Mot\Behat\Support\Api\Person;
use DvsaCommon\InputFilter\Account\ChangePasswordInputFilter;
use Dvsa\Mot\Behat\Support\Helper\TestSupportHelper;
use PHPUnit_Framework_Assert as PHPUnit;

class PasswordContext implements Context
{
    /**
     * @var Person
     */
    private $person;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var TestSupportHelper
     */
    private $testSupportHelper;

    /**
     * @var SessionContext
     */
    private $sessionContext;


    private $data = [];

    public function __construct(
        Person $person,
        Session $session,
        TestSupportHelper $testSupportHelper
    )
    {
        $this->person = $person;
        $this->session = $session;
        $this->testSupportHelper = $testSupportHelper;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->sessionContext = $scope->getEnvironment()->getContext(SessionContext::class);
    }


    /**
     * @When user fills in :oldPassword, :newPassword, :confirmNewPassword
     */
    public function userFillsIn($oldPassword, $newPassword, $confirmNewPassword)
    {
        $this->data = [
            ChangePasswordInputFilter::FIELD_OLD_PASSWORD => $oldPassword,
            ChangePasswordInputFilter::FIELD_PASSWORD => $newPassword,
            ChangePasswordInputFilter::FIELD_PASSWORD_CONFIRM => $confirmNewPassword
        ];

    }

    /**
     * @Then my password is updated
     */
    public function myPasswordIsUpdated()
    {
        $this->sessionContext->getCurrentAccessToken();
        $response = $this->person->changePassword(
            $this->sessionContext->getCurrentAccessToken(),
            $this->sessionContext->getCurrentUserId(),
            $this->data
        );

        PHPUnit::assertEquals(200, $response->getStatusCode());
    }

    /**
     * @Then I can log in with new password :password
     */
    public function iCanLogInWithNewPassword($password)
    {
        $username = $this->sessionContext->getCurrentUser()->getUsername();
        $resposne = $this->session->startSession($username, $password);

        PHPUnit::assertEquals($username, $resposne->getUsername());
    }

    /**
     * @Then my password is not updated
     */
    public function myPasswordIsNotUpdated()
    {
        $this->sessionContext->getCurrentAccessToken();
        $response = $this->person->changePassword(
            $this->sessionContext->getCurrentAccessToken(),
            $this->sessionContext->getCurrentUserId(),
            $this->data
        );

        $errors = $response->getBody()->toArray()["errors"];
        $isEmpty = empty($errors);

        PHPUnit::assertFalse($isEmpty);
    }
}
