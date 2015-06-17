<?php

namespace DvsaAuthentication\Listener;

use DvsaApplicationLogger\TokenService\TokenServiceInterface;
use DvsaAuthentication\Model\Identity;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Log\LoggerInterface;
use Zend\Mvc\MvcEvent;

class WebAuthenticationListener
{
    const ERROR_REGEX_WHITE_LIST = 'The regex defined for the whiteList route is not valid: %s';

    private $authenticationService;
    private $tokenService;
    private $logger;
    private $config;

    public function __construct(
        AuthenticationService $authenticationService,
        TokenServiceInterface $tokenService,
        LoggerInterface $logger,
        $config
    ) {
        $this->authenticationService = $authenticationService;
        $this->tokenService = $tokenService;
        $this->logger = $logger;
        $this->config = $config;
    }

    public function __invoke(MvcEvent $event)
    {
        $shouldAuthenticate = true;
        $route = $event->getRequest()->getUri()->getPath();
        foreach ($this->config['dvsa_authentication']['whiteList'] as $preg) {
            try {
                if (preg_match($preg, $route) > 0) {
                    $shouldAuthenticate = false;
                    break;
                }
            } catch (\Exception $e) {
                $this->logger->debug(sprintf(self::ERROR_REGEX_WHITE_LIST, $e->getMessage()));
                $shouldAuthenticate = true;
            }
        }

        if ($this->authenticationService->hasIdentity()) {
            /** @var Identity $identity */
            $identity = $this->authenticationService->getIdentity();
            $shouldAuthenticate = $identity->getAccessToken() !== $this->tokenService->getToken();
        }

        if ($shouldAuthenticate) {
            // todo: dirtyfix, here we should erase all session, but it causes problems with authentication service
            unset($_SESSION['Account\Service\ClaimAccountService']);
            unset($_SESSION[\DvsaMotTest\Service\VehicleContainer::class]);

            /** @var Result $result */
            $result = $this->authenticationService->authenticate();
            if ($result->getCode() === Result::FAILURE_CREDENTIAL_INVALID) {
                $baseWebUrl = $this->config['baseUrl'];
                $logoutUrl = $this->config['dvsa_authentication']['openAM']['logout_url'];
                header('Location: ' . $logoutUrl . $baseWebUrl);
                die();
            }
        }
    }
}
