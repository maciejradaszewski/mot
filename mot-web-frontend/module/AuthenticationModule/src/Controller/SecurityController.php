<?php

namespace Dvsa\Mot\Frontend\AuthenticationModule\Controller;

use Account\Service\ExpiredPasswordService;
use Core\Controller\AbstractDvsaActionController;
use Dashboard\Controller\UserHomeController;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\AuthenticationFailureViewModelBuilder;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\GotoUrlService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\IdentitySessionStateService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\LoginCsrfCookieService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\WebLoginService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\WebAuthenticationCookieService;
use DvsaCommon\Authn\AuthenticationResultCode;
use DvsaCommon\Dto\Authn\AuthenticationResponseDto;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Session\ManagerInterface;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Result;

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

    private $expiredPasswordService;

    private $loginCsrfCookieService;

    /** @var AuthenticationFailureViewModelBuilder */
    private $failureViewModelBuilder;

    /**
     * @param Request $request
     * @param Response $response
     * @param GotoUrlService $loginGotoService
     * @param WebAuthenticationCookieService $cookieService
     * @param IdentitySessionStateService $identitySessionStateService
     * @param WebLoginService $loginService
     * @param ManagerInterface $sessionManager
     * @param ExpiredPasswordService $expiredPasswordService
     * @param LoginCsrfCookieService $loginCsrfCookieService
     */
    public function __construct(
        Request $request,
        Response $response,
        GotoUrlService $loginGotoService,
        WebAuthenticationCookieService $cookieService,
        IdentitySessionStateService $identitySessionStateService,
        WebLoginService $loginService,
        ManagerInterface $sessionManager,
        ExpiredPasswordService $expiredPasswordService,
        LoginCsrfCookieService $loginCsrfCookieService,
        AuthenticationService $authenticationService,
        AuthenticationFailureViewModelBuilder $failureViewModelBuilder
    )
    {
        $this->request = $request;
        $this->response = $response;
        $this->gotoService = $loginGotoService;
        $this->authenticationCookieService = $cookieService;
        $this->identitySessionStateService = $identitySessionStateService;
        $this->loginService = $loginService;
        $this->sessionManager = $sessionManager;
        $this->expiredPasswordService = $expiredPasswordService;
        $this->loginCsrfCookieService = $loginCsrfCookieService;
        $this->failureViewModelBuilder = $failureViewModelBuilder;
        $this->authenticationService = $authenticationService;
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
            $csrfToken = $this->loginCsrfCookieService->addCsrfCookie($this->response);
            return (
            new ViewModel(
                [
                    'forgottenPasswordRoute' => self::ROUTE_FORGOTTEN_PASSWORD,
                    'gotoUrl' => $goto,
                    'loginCheckRoute' => self::ROUTE_LOGIN_GET,
                    'pageTitle' => self::PAGE_TITLE,
                    'csrfToken' => $csrfToken
                ]
            )
            )->setTemplate('authentication/login');
        }
    }

    /**
     * @return ViewModel
     */
    public function onPostLoginAction()
    {
        $isCsrfTokenValid = $this->loginCsrfCookieService->validate($this->request);
        if (false === $isCsrfTokenValid) {
            return $this->redirect()->toRoute(self::ROUTE_LOGIN_GET);
        }

        $request = $this->request;
        $username = $request->getPost(self::FIELD_USERNAME);
        $password = $request->getPost(self::FIELD_PASSWORD);

        $this->initializeSessionOnLogon();

        /** @var AuthenticationResponseDto $authnDto */
        $authnDto = $this->loginService->login($username, $password);

        if ($authnDto->getAuthnCode() !== AuthenticationResultCode::SUCCESS) {
            return $this->failureViewModelBuilder->createFromAuthenticationResponse($authnDto);
        }

        $this->authenticationCookieService->setUpCookie($authnDto->getAccessToken());

        // to be extracted to API
        $this->expiredPasswordService->sentExpiredPasswordNotificationIfNeeded($authnDto->getAccessToken(), $username);

        $rawGoto = $request->getPost(self::PARAM_GOTO);
        $goto = $this->gotoService->decodeGoto($rawGoto);
        if ($goto) {
            return $this->redirect()->toUrl($goto);
        } else {
            return $this->redirect()->toRoute(UserHomeController::ROUTE);
        }
    }

    private function initializeSessionOnLogon()
    {
        $this->sessionManager->regenerateId(true);
    }

    public function getUrlService()
    {
        return $this->gotoService;
    }
}
