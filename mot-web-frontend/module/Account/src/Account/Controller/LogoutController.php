<?php

namespace Account\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Header\SetCookie;

/**
 * Class LogoutController
 * @package Account\Controller
 */
class LogoutController extends AbstractActionController
{
    public function indexAction()
    {
        /** @var \Zend\Authentication\AuthenticationService $auth */
        $auth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        $auth->clearIdentity();

        $config = $this->getServiceLocator()->get('config');

        $this->redirect()->toUrl($config['openAm']['url'] . '/secure/UI/Logout');
    }
}
