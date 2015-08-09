<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModuleTest\Controller;

use CoreTest\Controller\AbstractLightWebControllerTest;
use Dvsa\Mot\Frontend\AuthenticationModule\Controller\LogoutController;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\WebLogoutService;

class LogoutControllerTest  extends AbstractLightWebControllerTest
{
    const DAS_LOGOUT_URL = 'http://openam.mot.gov.uk:8080/secureLogin/UI/Logout?&goto=http%3A%2F%2Fmot-web-frontend.mot.gov.uk%2F';

    /**
     * @var WebLogoutService
     */
    private $logoutService;

    public function setUp()
    {
        parent::setUp();

        $this->logoutService = $this
            ->getMockBuilder(WebLogoutService::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testWithDasDisabled()
    {
        $logoutWithDas = false;
        $dasLogoutUrl = null;
        $this->setController(new LogoutController($this->logoutService, $logoutWithDas, $dasLogoutUrl));

        $this->expectRedirect('login');
        $this->getController()->logoutAction();
    }

    public function testWithDasEnabled()
    {
        $logoutWithDas = true;
        $dasLogoutUrl = self::DAS_LOGOUT_URL;
        $this->setController(new LogoutController($this->logoutService, $logoutWithDas, $dasLogoutUrl));

        $this->expectRedirectToUrl(self::DAS_LOGOUT_URL);
        $this->getController()->logoutAction();
    }
}
