<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModuleTest\Factory\Controller;

use Account\Service\ExpiredPasswordService;
use CoreTest\Controller\AbstractLightWebControllerTest;
use Dashboard\Controller\UserHomeController;
use Dvsa\Mot\Frontend\AuthenticationModule\Controller\SecurityController;
use Dvsa\Mot\Frontend\AuthenticationModule\Model\IdentitySessionState;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\OpenAMAuthenticator;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthFailure;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthSuccess;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\GotoUrlService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\IdentitySessionStateService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\WebAuthenticationCookieService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\GotoUrlValidatorService;
use Dvsa\OpenAM\OpenAMAuthProperties;
use DvsaCommonTest\TestUtils\XMock;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;
use Zend\Session\ManagerInterface;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;

/**
 */
class SecurityControllerTest extends AbstractLightWebControllerTest
{
    const VALID_GOTO_URL = 'http://goto.url.com/url';
    const BASE_GOTO = 'http://goto.url.com';
    const INVALID_GOTO_URL = 'http://goto.com/aaa';

    /** @var OpenAMAuthenticator */
    private $authenticator;

    /** @var GotoUrlService */
    private $gotoService;

    /** @var WebAuthenticationCookieService $cookieService */
    private $cookieService;

    /** @var  IdentitySessionStateService $identitySessionStateService */
    private $identitySessionStateService;

    /** @var AuthenticationService $authenticationService */
    private $authenticationService;

    /** @var  Request */
    private $request;

    private $sessionManager;

    protected function setUp()
    {
        parent::setUp();
        $this->authenticator = XMock::of(OpenAMAuthenticator::class);

        $this->gotoService = $this->buildGotoUrlService();

        $this->setController($this->buildController());

    }

    public function testOnGetLoginAction_notAuthenticated()
    {
        $this->identitySessionState(new IdentitySessionState(false, false));

        $vm = $this->getController()->onGetLoginAction();
        $this->assertEquals('', $vm->getVariables['goto']);
        $this->assertEquals($vm->getTemplate(), 'authentication/login');
    }

    public function testOnGetLoginAction_whenAuthenticated_and_validGoto_shouldRedirectToGotoUrl()
    {
        $this->gotoService = $this->buildGotoUrlService();
        $this->gotoService
            ->expects($this->any())
            ->method('isValidGoto')
            ->willReturn(true);

        $this->setController($this->buildController());

        $this->identitySessionState(new IdentitySessionState(true, false));
        $this->withGotoUrlAsQueryParam(self::VALID_GOTO_URL);
        $this->expectRedirectToUrl(self::VALID_GOTO_URL);
        $this->getController()->onGetLoginAction();
    }

    public function testOnGetLoginAction_whenAuthenticated_and_invalidGoto_shouldRedirectToGotoUrl()
    {

        $this->gotoService = $this->buildGotoUrlService();
        $this->gotoService
            ->expects($this->any())
            ->method('isValidGoto')
            ->willReturn(false);

        $this->setController($this->buildController());

        $this->identitySessionState(new IdentitySessionState(true, false));
        $this->withGotoUrlAsQueryParam(self::INVALID_GOTO_URL);
        $this->expectRedirect(UserHomeController::ROUTE);
        $this->getController()->onGetLoginAction();
    }

    public function testOnGetLoginAction_whenNotAuthenticated_and_validGoto_shouldShowLoginPage()
    {

        $this->gotoService = $this->buildGotoUrlService();
        $this->gotoService
            ->expects($this->any())
            ->method('isValidGoto')
            ->willReturn(true);

        $this->setController($this->buildController());

        $this->identitySessionState(new IdentitySessionState(false, false));
        $this->withGotoUrlAsQueryParam(self::VALID_GOTO_URL);

        $vm = $this->getController()->onGetLoginAction();

        $this->assertEquals('authentication/login', $vm->getTemplate());
        $this->assertEquals($this->gotoService->encodeGoto(self::VALID_GOTO_URL), $vm->getVariable('gotoUrl'));
    }

    public function testOnGetLoginAction_whenAuthenticated_and_toldToClearIdentity_shouldClearIdentity()
    {
        $this->identitySessionState(new IdentitySessionState(true, true));
        $this->authenticationService->expects($this->once())->method('clearIdentity');
        $this->expectRedirect(UserHomeController::ROUTE);

        $this->getController()->onGetLoginAction();
    }

    public function testOnPostLoginAction_whenInvalidCredentials_shouldShowErrorScreen()
    {
        $viewModel = (new ViewModel())->setTemplate('authentication/failed/default');
        $authResponse = new OpenAMAuthFailure(OpenAMAuthProperties::CODE_AUTHENTICATION_FAILED, $viewModel);

        $this->authenticator
            ->expects($this->once())->method('authenticate')
            ->willReturn($authResponse);
        $this->expectSessionIdRegenerated(false);
        $this->expectAuthCookieWasNotSetUp();

        $vm = $this->getController()->onPostLoginAction();

        $this->assertEquals('authentication/failed/default', $vm->getTemplate());
    }

    public function testOnPostLoginAction_whenAccountLocked_shouldShowErrorScreen()
    {
        $viewModel = (new ViewModel())->setTemplate('authentication/failed/default');
        $authResponse = new OpenAMAuthFailure(OpenAMAuthProperties::CODE_AUTHENTICATION_FAILED, $viewModel);

        $this->authenticator
            ->expects($this->once())->method('authenticate')
            ->willReturn($authResponse);

        $this->expectSessionIdRegenerated(false);
        $this->expectAuthCookieWasNotSetUp();

        $vm = $this->getController()->onPostLoginAction();

        $this->assertEquals('authentication/failed/default', $vm->getTemplate());
    }

    public function testOnPostLoginAction_whenLoginSuccess_and_gotoValid_shouldRedirectToGotoUrl()
    {
        $this->gotoService = $this->buildGotoUrlService();
        $this->gotoService
            ->expects($this->any())
            ->method('isValidGoto')
            ->willReturn(true);

        $this->gotoService
            ->method('decodeGoto')
            ->willReturn(self::VALID_GOTO_URL);

        $expiryPasswordService = XMock::of(ExpiredPasswordService::class);
        $expiryPasswordService
            ->expects($this->once())
            ->method("sentExpiredPasswordNotificationIfNeeded");
        $this->setController($this->buildController($expiryPasswordService));

        $token = 'xifuvdv09RGEgrege';
        $this->authenticator
            ->expects($this->once())
            ->method('authenticate')
            ->willReturn(new OpenAMAuthSuccess($token));

        $this->withGotoUrlAsPostParam($this->gotoService->encodeGoto(self::VALID_GOTO_URL));
        $this->expectSessionIdRegenerated(true);
        $this->expectAuthCookieWasSetUp($token);

        $this->expectRedirectToUrl(self::VALID_GOTO_URL);
        $this->getController()->onPostLoginAction();
    }

    public function testOnPostLoginAction_whenLoginSuccess_and_invalidGoto_shouldRedirectToGotoUrl()
    {
        $expiryPasswordService = XMock::of(ExpiredPasswordService::class);
        $expiryPasswordService
            ->expects($this->once())
            ->method("sentExpiredPasswordNotificationIfNeeded");
        $this->setController($this->buildController($expiryPasswordService));

        $token = 'xifuvdv09RGEgrege';
        $this->authenticator->expects($this->once())->method('authenticate')
            ->willReturn(new OpenAMAuthSuccess($token));
        $this->expectSessionIdRegenerated(true);
        $this->expectAuthCookieWasSetUp($token);

        $this->expectRedirect(UserHomeController::ROUTE);
        $this->getController()->onPostLoginAction();
    }

    private function identitySessionState(IdentitySessionState $sessionState)
    {
        $this->identitySessionStateService->expects($this->atLeastOnce())
            ->method('getState')->willReturn($sessionState);
    }

    private function withGotoUrlAsQueryParam($url)
    {
        $this->request->setQuery(new Parameters(['goto' => $url]));
    }

    private function withGotoUrlAsPostParam($url)
    {
        $this->request->setPost(new Parameters(['goto' => $url]));
    }

    private function expectSessionIdRegenerated($decision)
    {
        if ($decision) {
            $this->sessionManager->expects($this->atLeastOnce())->method('regenerateId');
        } else {
            $this->sessionManager->expects($this->never())->method($this->anything());
        }
    }

    private function expectAuthCookieWasSetUp($token)
    {
        $this->cookieService->expects($this->atLeastOnce())->method('setUpCookie')->with($token);
    }

    private function expectAuthCookieWasNotSetUp()
    {
        $this->cookieService->expects($this->never())->method('setUpCookie');
    }

    private function buildGotoUrlService()
    {
        return $this->getMockBuilder(GotoUrlService::class)->disableOriginalConstructor()->getMock();
    }

    private function buildController($expiryPasswordService = null)
    {
        $this->cookieService = XMock::of(WebAuthenticationCookieService::class);
        $this->identitySessionStateService = XMock::of(IdentitySessionStateService::class);
        $this->authenticationService = XMock::of(AuthenticationService::class);
        $this->request = new Request();
        $this->sessionManager = XMock::of(ManagerInterface::class);

        if (is_null($expiryPasswordService)) {
            $expiryPasswordService = XMock::of(ExpiredPasswordService::class);
            $expiryPasswordService
                ->expects($this->exactly(0))
                ->method("sentExpiredPasswordNotificationIfNeeded");
        }

        $controller = new SecurityController(
            $this->request,
            $this->authenticator,
            $this->gotoService,
            $this->cookieService,
            $this->identitySessionStateService,
            $this->authenticationService,
            $this->sessionManager,
            $expiryPasswordService
        );

        return $controller;
    }
}
