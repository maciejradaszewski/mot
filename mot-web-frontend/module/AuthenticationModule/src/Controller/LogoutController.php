<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModule\Controller;

use Dvsa\Mot\Frontend\AuthenticationModule\Service\WebLogoutService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Logout Controller is used to logout user sessions.
 */
class LogoutController extends AbstractActionController
{
    const ROUTE_LOGOUT = 'logout';

    /**
     * @var WebLogoutService
     */
    private $logoutService;

    /**
     * @var bool
     */
    private $logoutWithDas;

    /**
     * @var bool
     */
    private $dasLogoutUrl;

    /**
     * @param WebLogoutService $logoutService
     * @param bool|false       $logoutWithDas
     * @param null             $dasLogoutUrl
     */
    public function __construct(WebLogoutService $logoutService, $logoutWithDas = false, $dasLogoutUrl = null)
    {
        $this->logoutService = $logoutService;
        $this->logoutWithDas = $logoutWithDas;
        $this->dasLogoutUrl = $dasLogoutUrl;
    }

    /**
     * @return \Zend\Http\Response
     */
    public function logoutAction()
    {
        $this->logoutService->logout();

        // When using the DAS redirect to the OpenAM logout URL
        if (true === $this->logoutWithDas) {
            return (null !== $this->dasLogoutUrl) ?
                $this->redirect()->toUrl($this->dasLogoutUrl) : $this->redirect()->toRoute('user-home');
        }

        return $this->redirect()->toRoute('login');
    }
}
