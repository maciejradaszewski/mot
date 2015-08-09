<?php
namespace Core\Controller;

use Core\Service\MotFrontendAuthorisationServiceInterface;
use Dvsa\Mot\Frontend\AuthenticationModule\Model\MotFrontendIdentityInterface;
use DvsaCommon\Auth\NotLoggedInException;
use Zend\Session\Container;

/**
 * Superclass of controllers that use the logged-in user's identity and roles
 */
abstract class AbstractAuthActionController extends AbstractDvsaActionController
{
    /**
     * @var \Zend\Session\Container;
     */
    protected $motSession;

    /**
     * Inject a client for simpler unit testing.
     *
     * @param $client
     */
    public function setRequestClient($client)
    {
        $this->restClient = $client;
    }

    /**
     * @return MotFrontendIdentityInterface
     */
    protected function getIdentity()
    {
        return $this->getIdentityProviderService()->getIdentity();
    }

    protected function verifyIsAuthenticated()
    {
        $auth = $this->getServiceLocator()->get('ZendAuthenticationService');

        if (!$auth->hasIdentity()) {
            throw new NotLoggedInException();
        }
    }

    protected function assertGranted($permission, $resource = null)
    {
        $this->getAuthorizationService()->assertGranted($permission, $resource);
    }

    protected function getUserDisplayDetails()
    {
        $userDisplayDetails = [
            'user' => null,
        ];
        if ($this->getIdentity()) {
            $userDisplayDetails['user'] = $this->getIdentity();
        }

        $sm = $this->getServiceLocator();
        $motSession = $sm->get('MotSession');

        if (isset($motSession->slots)) {
            $userDisplayDetails['slotsDetails'] = $motSession->slots;
        }

        // TODO Reinstate this when unit tests can use a logger
        //$this->getLogger()->debug("Current user: " . print_r($userDisplayDetails, true));

        return $userDisplayDetails;
    }

    /**
     * @return \Dvsa\Mot\Frontend\AuthenticationModule\Model\MotFrontendIdentityInterface
     * @deprecated
     */
    protected function getIdentityProviderService()
    {
        return $this->getServiceLocator()->get('MotIdentityProvider');
    }

    /**
     * @return MotFrontendAuthorisationServiceInterface
     * @deprecated
     */
    public function getAuthorizationService()
    {
        return $this->serviceLocator->get("AuthorisationService");
    }
}
