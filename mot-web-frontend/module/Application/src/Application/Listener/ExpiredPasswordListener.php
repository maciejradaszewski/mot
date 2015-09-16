<?php

namespace Application\Listener;

use Dvsa\Mot\Frontend\AuthenticationModule\Model\Identity;
use Dvsa\OpenAM\Model\OpenAMLoginDetails;
use Dvsa\OpenAM\OpenAMClientInterface;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Date\DateTimeHolder;
use Zend\Log\LoggerInterface;
use Zend\Mvc\MvcEvent;

/**
 * User who has expired password has access to only reset password functionality
 *
 * Class PasswordExpiredListener
 * @package Application\Listener
 */
class ExpiredPasswordListener
{
    const CLAIM_ACCOUNT_DISPLAY_PIN_ROUTE = 'account/claim/displayPin';

    /**
     * @var MotIdentityProviderInterface
     */
    private $identityProvider;

    private $openAmClient;

    private $timeHolder;

    private $logger;

    private $realm;

    private $gracePeriod;

    private $whiteList = [
        'login',
        'logout',
        'forgotten-password/update-password',
        'account/claim',
        'account/claim/confirmEmailAndPassword',
        'account/claim/setSecurityQuestion',
        'account/claim/displayPin',
        'account/claim/review',
        'account/claim/reset',
        'user-home/profile/change-password',
        'user-home/profile/change-password/confirmation'
    ];

    public function __construct(
        MotIdentityProviderInterface $identityProvider,
        OpenAMClientInterface $openAmClient,
        DateTimeHolder $timeHolder,
        LoggerInterface $logger,
        $realm,
        $gracePeriod
    )
    {
        $this->identityProvider = $identityProvider;
        $this->openAmClient = $openAmClient;
        $this->timeHolder = $timeHolder;
        $this->logger = $logger;
        $this->realm = $realm;
        $this->gracePeriod = $gracePeriod;
    }

    public function __invoke(MvcEvent $event)
    {
        $routeName = $event->getRouteMatch()->getMatchedRouteName();
        $identity = $this->getIdentity();

        if (!$identity) {
            // not logged in, move on
            return;
        }

        if (!$this->isRouteRestricted($routeName)) {
            // the route is white-listed so we're good
            return;
        }

        if (!$identity->hasPasswordExpired()) {
            // we don't care when password is still valid
            return;
        }

        $expirationDate = $this->getExpiryDate();

        $now = $this->timeHolder->getCurrent();

        if ($now < $expirationDate) {
            // password has not expired, so no problem

            $identity->setPasswordExpired(false);

            return;
        }

        $redirectUrl = $event->getRouter()->assemble([], ['name' => 'user-home/profile/change-password']);

        if ($redirectUrl) {
            $response = $event->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $redirectUrl);
            $response->setStatusCode(302);
            $response->sendHeaders();
            $event->stopPropagation();
        }
    }

    private function getExpiryDate()
    {
        $identity = $this->identityProvider->getIdentity();

        $expirationDate = $this->openAmClient->getPasswordExpiryDate(new OpenAMLoginDetails($identity->getUsername(), null, $this->realm));

        if (!$this->gracePeriod) {
            throw new \InvalidArgumentException("'password_expiry_grace_period' is missing from configuration in mot-web-frontend.");
        }

        $expirationDate = $expirationDate->modify("- " . $this->gracePeriod);

        return $expirationDate;
    }

    private function isRouteRestricted($routeName)
    {
        return !in_array($routeName, $this->whiteList);
    }

    /**
     * @return Identity
     */
    private function getIdentity()
    {
        return $this->identityProvider->getIdentity();
    }
}
