<?php

namespace Application\Listener;

use Account\Service\ExpiredPasswordService;
use Dvsa\Mot\Frontend\AuthenticationModule\Model\Identity;
use Dvsa\OpenAM\Model\OpenAMLoginDetails;
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

    private $timeHolder;

    private $logger;

    private $expiredPasswordService;

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
        'user-home/profile/change-password/confirmation',
        'newProfile/profile/change-password',
        'newProfile/profile/change-password/confirmation'
    ];

    public function __construct(
        MotIdentityProviderInterface $identityProvider,
        DateTimeHolder $timeHolder,
        LoggerInterface $logger,
        ExpiredPasswordService $expiredPasswordService
    )
    {
        $this->identityProvider = $identityProvider;
        $this->timeHolder = $timeHolder;
        $this->logger = $logger;
        $this->expiredPasswordService = $expiredPasswordService;
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

        $expirationDate =
            $this->expiredPasswordService->calculatePasswordChangePromptDate($identity->getPasswordExpiryDate());

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
