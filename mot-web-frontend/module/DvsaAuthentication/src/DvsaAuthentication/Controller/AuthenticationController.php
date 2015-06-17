<?php
namespace DvsaAuthentication\Controller;

use DvsaMotTest\Controller\AbstractDvsaMotTestController;
use DvsaMotTest\Controller\LocationSelectController;
use Dashboard\Controller\UserHomeController;
use Zend\Authentication\Result;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\View\Model\ViewModel;

/**
 *  logout
 */
class AuthenticationController extends AbstractDvsaMotTestController
{
    const MESSAGE_BAD_CREDENTIALS = "There was an error with your Username/Password combination. Please try again.";
    const MESSAGE_UNKNOWN_RESULT = "There was an error logging into the system. Please try again.";
    const MESSAGE_SUCCESSFULLY_LOGGED_OUT = 'You have been successfully logged out';

    const ROUTE_LOGOUT = 'logout';
    const ROUTE_USER_HOME = UserHomeController::ROUTE;

    public function logoutAction()
    {
        $client = $this->getRestClient();
        $client->delete('session');
        // This includes the form/button in userDetails.phtml
        (new SessionManager())->destroy(['clearStorage' => true]);
        //$this->addInfoMessages(self::MESSAGE_SUCCESSFULLY_LOGGED_OUT);
        $sm = $this->getServiceLocator();
        $config = $sm->get('config');
        $baseWebUrl = $config['baseUrl'];
        $logoutUrl = $config['dvsa_authentication']['openAM']['logout_url'];

        return $this->redirect()->toUrl($logoutUrl . $baseWebUrl);
    }

    protected function redirectToSelectVtsLocation()
    {
        return $this->redirect()->toRoute(LocationSelectController::ROUTE);
    }

    protected function getLoginErrorMessage($code)
    {
        switch ($code) {
            case Result::SUCCESS:
                break;
            case Result::FAILURE:
            case Result::FAILURE_IDENTITY_NOT_FOUND:
            case Result::FAILURE_CREDENTIAL_INVALID:
                return self::MESSAGE_BAD_CREDENTIALS;
            default:
                return self::MESSAGE_UNKNOWN_RESULT;
        }
    }

    protected function clearSession(Container $session)
    {
        $session->getManager()->getStorage()->clear();
    }

    /**
     * @param $username
     * @param $password
     *
     * @return \Zend\Http\Response
     */
    private function loginAndRedirect($username, $password)
    {
        $sm = $this->getServiceLocator();
        $authAdapter = $sm->get('AuthAdapter');

        $authAdapter
            ->setIdentity($username)
            ->setCredential($password);
        $auth = $sm->get('ZendAuthenticationService');
        $result = $auth->authenticate();

        if ($result) {
            $this->addErrorMessages($this->getLoginErrorMessage($result->getCode()));
        }

        if ($auth->hasIdentity()) {
            return $this->redirect()->toRoute(self::ROUTE_USER_HOME);
        } else {
            return false;
        }
    }
}
