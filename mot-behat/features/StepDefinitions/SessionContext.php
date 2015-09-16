<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Datasource\Authentication;
use Dvsa\Mot\Behat\Support\Api\AccountClaim;
use Dvsa\Mot\Behat\Support\Api\Session;
use Dvsa\Mot\Behat\Support\Api\Session\AuthenticatedUser;
use Dvsa\Mot\Behat\Support\Api\TempPasswordChange;
use Dvsa\Mot\Behat\Support\Helper\TestSupportHelper;

class SessionContext implements Context
{
    /**
     * @var AccountClaim
     */
    private $accountClaim;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var AuthenticatedUser
     */
    private $currentUser = null;

    /**
     * @var AccountClaimContext
     */
    private $accountClaimContext;

    /**
     * @var TempPasswordChangeContext
     */
    private $tempPasswordChangeContext;

    /**
     * @var VtsContext
     */
    private $vtsContext;

    /**
     * @var authorisedExaminerContext
     */
    private $authorisedExaminerContext;

    /**
     * @param AccountClaim       $accountClaim
     * @param Session            $session
     * @param TestSupportHelper  $testSupportHelper
     */
    public function __construct(AccountClaim $accountClaim, Session $session, TestSupportHelper $testSupportHelper)
    {
        $this->accountClaim      = $accountClaim;
        $this->session           = $session;
        $this->testSupportHelper = $testSupportHelper;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->accountClaimContext       = $scope->getEnvironment()->getContext(AccountClaimContext::class);
        $this->tempPasswordChangeContext = $scope->getEnvironment()->getContext(TempPasswordChangeContext::class);
        $this->vtsContext = $scope->getEnvironment()->getContext(VtsContext::class);
        $this->authorisedExaminerContext = $scope->getEnvironment()->getContext(AuthorisedExaminerContext::class);
    }

    /**
     * @Given I am logged in as a Tester
     */
    public function iAmLoggedInAsATester()
    {
        $this->currentUser = $this->session->logInAsTester($this->testSupportHelper);
    }

    /**
     * @Given I am logged in as a Special Notice broadcast user
     */
    public function iAmLoggedInAsASpecialNoticeBroadcastUser()
    {
        $username = Authentication::LOGIN_CRON_JOB_USER;
        $password = Authentication::PASSWORD_DEFAULT;

        $this->currentUser = $this->session->startSession($username, $password);
    }

    /**
     * @Given /^I am logged in as an? Scheme User$/
     */
    public function iAmLoggedInAsASchemeUser()
    {
        $schemeUserService = $this->testSupportHelper->getSchemeUserService();
        $user              = $schemeUserService->create([]);

        $this->currentUser  = $this->session->startSession($user->data['username'], $user->data['password']);
    }

    /**
     * @Given /^I am not logged in$/
     * @Given I am an unregistered user
     */
    public function iAmNotLoggedIn()
    {
        $this->currentUser = null;
    }

    /**
     * @Given /^I am logged in as an? Area Office User$/
     */
    public function iAmLoggedInAsAnAreaOfficeUser()
    {
        $areaOffice1Service = $this->testSupportHelper->getAreaOffice1Service();
        $user               = $areaOffice1Service->create([]);
        $this->currentUser  = $this->session->startSession($user->data['username'], $user->data['password']);
    }

    /**
     * @Given /^I am logged in as an? Area Office User 2$/
     */
    public function iAmLoggedInAsAnAreaOfficeUser2()
    {
        $areaOffice2Service = $this->testSupportHelper->getAreaOffice2Service();
        $user               = $areaOffice2Service->create([]);
        $this->currentUser  = $this->session->startSession($user->data['username'], $user->data['password']);
    }

    /**
     * @Given /^I am logged in as an? Cron User$/
     */
    public function iAmLoggedInAsAnCronUser()
    {
        $cronUserService = $this->testSupportHelper->getCronUserService();
        $user               = $cronUserService->create([]);
        $this->currentUser  = $this->session->startSession($user->data['username'], $user->data['password']);
    }


    /**
     * @Given I am logged in as a Finance User
     */
    public function iAmLoggedInAsAFinanceUser()
    {
        $financeUserService = $this->testSupportHelper->getFinanceUserService();
        $user               = $financeUserService->create([]);
        $this->currentUser  = $this->session->startSession($user->data['username'], $user->data['password']);
    }

    /**
     * @Given /^I'm authenticated with my username and password (.*) (.*)$/
     *
     * @param $username
     * @param $password
     */
    public function iMAuthenticatedWithMyUsernameAndPassword($username, $password)
    {
        //use generic password if $password is "default"
        if (strcasecmp($password, 'DEFAULT') == 0) {
            $password = Authentication::PASSWORD_DEFAULT;
        }

        $this->currentUser = $this->session->startSession($username, $password);
    }

    /**
     * @Given I log in as a VM10519User
     * @Given I am logged in as a VM10519User
     */
    public function iAmLoggedInAsAVM10519User()
    {
        $sveService = $this->testSupportHelper->getVM10519UserService();
        $user                   = $sveService->create([]);
        $this->currentUser      = $this->session->startSession(
            $user->data['username'],
            $user->data['password']
        );
    }

    /**
     * @Given I log in as a Vehicle Examiner
     * @Given /^I am logged in as an? Vehicle Examiner$/
     */
    public function iAmLoggedInAsAVehicleExaminer()
    {
        $vehicleExaminerService = $this->testSupportHelper->getVehicleExaminerService();
        $user                   = $vehicleExaminerService->create([]);
        $this->currentUser      = $this->session->startSession(
            $user->data['username'],
            $user->data['password']
        );
    }

    /**
     * @Given /^I am logged in as an? Customer Service Operator$/
     */
    public function iAmLoggedInAsACustomerServiceOperator()
    {
        $cscoService       = $this->testSupportHelper->getCscoService();
        $user              = $cscoService->create([]);
        $this->currentUser = $this->session->startSession(
            $user->data['username'],
            $user->data['password']
        );
    }

    /**
     * @Given I am logged in as a Customer Service Manager
     */
    public function iAmLoggedInAsACustomerServiceManager()
    {
        $csmService        = $this->testSupportHelper->getCsmService();
        $user              = $csmService->create([]);
        $this->currentUser = $this->session->startSession(
            $user->data['username'],
            $user->data['password']
        );
    }

    /**
     * @Given I am logged in as a Area Office 1
     */
    public function iAmLoggedInAsAnAreaOffice1()
    {
        $ao1user           = $this->testSupportHelper->getAreaOffice1Service();
        $user              = $ao1user->create([]);
        $this->currentUser = $this->session->startSession(
            $user->data['username'],
            $user->data['password']
        );
    }

    /**
     * @Given /^I am logged in as an? DVLA Manager$/
     */
    public function iAmLoggedInAsADVLAManager()
    {
        $dvlaManagerService = $this->testSupportHelper->getDVLAManagerService();
        $user               = $dvlaManagerService->create([]);
        $this->currentUser  = $this->session->startSession(
            $user->data['username'],
            $user->data['password']
        );
    }

    /**
     * @Given I am logged in as a Scheme Manager
     */
    public function iAmLoggedInAsASchemeManager()
    {
        $schemeManagerService = $this->testSupportHelper->getSchemeManagerService();
        $schemeManager        = $schemeManagerService->create([]);

        $this->currentUser = $this->session->startSession(
            $schemeManager->data['username'],
            $schemeManager->data['password']
        );
    }


    /**
     * @Given I am logged in as a Tester with an unclaimed account
     */
    public function iAmLoggedInAsATesterWithAnUnclaimedAccount()
    {
        $testerService = $this->testSupportHelper->getTesterService();
        $tester        = $testerService->create([
            'accountClaimRequired' => true,
            'siteIds'              => [1],
        ]);

        $this->currentUser = $this->session->startSession($tester->data['username'], $tester->data['password']);

        $this->accountClaimContext->myAccountHasNotYetBeenClaimed();
    }

    /**
     * @Given I am logged in as a Tester with a Temp Password
     */
    public function iAmLoggedInAsATesterWithATempPassword()
    {
        $testerService = $this->testSupportHelper->getTesterService();
        $tester        = $testerService->create([
            'passwordChangeRequired' => true,
            'siteIds'                => [1],
        ]);

        $this->currentUser = $this->session->startSession($tester->data['username'], $tester->data['password']);

        $this->tempPasswordChangeContext->myAccountHasBeenFlaggedAsTempPassword();
    }

    /**
     * @return AuthenticatedUser
     */
    public function getCurrentUser()
    {
        if (null === $this->currentUser) {
            throw new \BadMethodCallException('Current user is not available. Have you forgotten to authenticate?');
        }

        return $this->currentUser;
    }

    /**
     * @return string
     */
    public function getCurrentAccessToken()
    {
        return $this->getCurrentUser()->getAccessToken();
    }

    /**
     * @return string
     */
    public function getCurrentUserId()
    {
        return $this->getCurrentUser()->getUserId();
    }

    /**
     * @return null|string
     */
    public function getCurrentAccessTokenOrNull()
    {
        try {
            return $this->getCurrentAccessToken();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getCurrentUserIdOrNull()
    {
        try {
            return $this->getCurrentUserId();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @Given I am authenticated as :username
     */
    public function iAmAuthenticatedAs($username)
    {
        $this->iMAuthenticatedWithMyUsernameAndPassword($username, Authentication::PASSWORD_DEFAULT);
    }

    /**
     * @Given I am logged in as :role
     */
    public function iAmLoggedInAs($role)
    {
        $this->iMAuthenticatedWithMyUsernameAndPassword($role, Authentication::PASSWORD_DEFAULT);
    }

    /**
     * @Given /^I am logged in as an? Authorised Examiner$/
     */
    public function iAmLoggedInAsAnAuthorisedExaminer()
    {
        $this->iMAuthenticatedWithMyUsernameAndPassword('aedm', Authentication::PASSWORD_DEFAULT);
    }

    /**
     * @Given I am logged in as an Area Office User to new site
     */
    public function iAmLoggedInAsAnAreaOfficeUserToNewSite()
    {
        $this->vtsContext->createSite();
        $this->iAmLoggedInAsAnAreaOfficeUser();
    }

    /**
     * @Given I am logged in as an Area Office User 2 to new site
     */
    public function iAmLoggedInAsAnAreaOfficeUser2ToNewSite()
    {
        $this->vtsContext->createSite();
        $this->iAmLoggedInAsAnAreaOfficeUser2();
    }

    /**
     * @Given I am logged in as a Site Manager to new site
     */
    public function iAmLoggedInAsASiteManagerToNewSite()
    {
        $this->vtsContext->createSite();
        $siteId = $this->vtsContext->getSite()["id"];
        $siteManagerService = $this->testSupportHelper->getSiteUserDataService();

        $data = [
            "siteIds" => [ $siteId ],
            "requestor" => [
                "username" => "schememgt",
                "password" => "Password1"
            ]
        ];
        $user               = $siteManagerService->create($data, "SITE-MANAGER");
        $this->currentUser  = $this->session->startSession($user->data['username'], $user->data['password']);
    }

    /**
     * @Given I am logged in as an Aedm to new organisation
     */
    public function iAmLoggedInAsAnAedmToNewOrganisation()
    {
        $ae = $this->authorisedExaminerContext->createAE();
        $aeId = $ae["id"];
        $aedmService = $this->testSupportHelper->getAedmService();

        $data = [
            "aeIds" => [ $aeId ],
            "requestor" => [
                "username" => "schememgt",
                "password" => "Password1"
            ]
        ];
        $user              = $aedmService->create($data);
        $this->currentUser = $this->session->startSession($user->data['username'], $user->data['password']);
    }

}
