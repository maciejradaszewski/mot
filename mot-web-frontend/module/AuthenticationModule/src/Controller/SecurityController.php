<?php

namespace Dvsa\Mot\Frontend\AuthenticationModule\Controller;

use Core\Controller\AbstractDvsaActionController;
use Dashboard\Controller\UserHomeController;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\OpenAMAuthenticator;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthFailure;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthSuccess;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\GotoUrlService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\IdentitySessionStateService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\WebAuthenticationCookieService;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;
use Zend\Session\ManagerInterface;
use Zend\Session\SessionManager;
use Zend\View\Model\ViewModel;

/**
 *  SecurityController handles user's login requests. Every resource provided by this controller is not firewalled.
 */
class SecurityController extends AbstractDvsaActionController
{
    const FIELD_PASSWORD = 'IDToken2';
    const FIELD_USERNAME = 'IDToken1';
    const PAGE_TITLE = 'MOT testing service';
    const PARAM_GOTO = 'goto';
    const ROUTE_FORGOTTEN_PASSWORD = 'forgotten-password';
    const ROUTE_LOGIN_GET = 'login';
    const ROUTE_LOGIN_POST = 'login';

    /**
     * @var OpenAMAuthenticator
     */
    private $authenticator;

    /**
     * @var WebAuthenticationCookieService
     */
    private $authenticationCookieService;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var GotoUrlService
     */
    private $gotoService;

    /**
     * @var IdentitySessionStateService
     */
    private $identitySessionStateService;

    /**
     * @var ManagerInterface
     */
    private $sessionManager;

    /**
     * @param Request                        $request
     * @param OpenAMAuthenticator            $authenticator
     * @param GotoUrlService                 $loginGotoService
     * @param WebAuthenticationCookieService $cookieService
     * @param IdentitySessionStateService    $identitySessionStateService
     * @param AuthenticationService          $authenticationService
     * @param ManagerInterface               $sessionManager
     */
    public function __construct(
        Request $request,
        OpenAMAuthenticator $authenticator,
        GotoUrlService $loginGotoService,
        WebAuthenticationCookieService $cookieService,
        IdentitySessionStateService $identitySessionStateService,
        AuthenticationService $authenticationService,
        ManagerInterface $sessionManager
    ) {
        $this->request = $request;
        $this->authenticator = $authenticator;
        $this->gotoService = $loginGotoService;
        $this->authenticationCookieService = $cookieService;
        $this->identitySessionStateService = $identitySessionStateService;
        $this->authenticationService = $authenticationService;
        $this->sessionManager = $sessionManager;
    }

    /**
     * @return ViewModel
     */
    public function loginAction()
    {
        $request = $this->request;
        if ($request->isPost()) {
            $response = $this->onPostLoginAction();
        } else {
            $response = $this->onGetLoginAction();
        }

        if ($response instanceof ViewModel) {
            $this->layout('layout/layout-govuk.phtml');
            $this->layout()->setVariables($response->getVariables());
        }

        return $response;
    }

    /**
     * When a user accesses the login page several checks should happen to decide whether to display the login page or
     * redirect to the user home.
     *
     * @return ViewModel
     */
    public function onGetLoginAction()
    {
        /* @var Request $request */
        $request = $this->request;

        $state = $this->identitySessionStateService->getState();

        $rawGoto = $request->getQuery(self::PARAM_GOTO);
        if ($state->isAuthenticated()) {
            if ($state->shouldClearIdentity()) {
                $this->authenticationService->clearIdentity();
            }
            if ($this->gotoService->isValidGoto($rawGoto)) {
                return $this->redirect()->toUrl($rawGoto);
            } else {
                return $this->redirect()->toRoute(UserHomeController::ROUTE);
            }
        } else {
            $goto = $this->gotoService->encodeGoto($rawGoto);

            return (new ViewModel([
                'forgottenPasswordRoute' => self::ROUTE_FORGOTTEN_PASSWORD,
                'gotoUrl'                => $goto,
                'loginCheckRoute'        => self::ROUTE_LOGIN_GET,
                'pageTitle'              => self::PAGE_TITLE,
            ]))->setTemplate('authentication/login');
        }
    }

    /**
     * @return ViewModel
     */
    public function onPostLoginAction()
    {
        $request = $this->request;
        $username = $request->getPost(self::FIELD_USERNAME);
        $password = $request->getPost(self::FIELD_PASSWORD);

        $result = $this->authenticator->authenticate($username, $password);
        if (false === $result->isSuccess()) {
            /* @var OpenAMAuthFailure $result */
            return $result->getViewModel();
        }
        $this->initializeSessionOnLogon();
        /* @var OpenAMAuthSuccess $result */
        $this->authenticationCookieService->setUpCookie($result->getToken());

        $rawGoto = $request->getPost(self::PARAM_GOTO);
        $goto = $this->gotoService->decodeGoto($rawGoto);
        if ($goto) {
            return $this->redirect()->toUrl($goto);
        } else {
            return $this->redirect()->toRoute(UserHomeController::ROUTE);
        }
    }

    /**
     */
    private function initializeSessionOnLogon()
    {
        $this->sessionManager->regenerateId(true);
    }

    public function getUrlService()
    {
        return $this->gotoService;
    }
}
