<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModule\OpenAM;

use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthenticationResponse;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthFailureBuilder;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthSuccess;
use Dvsa\OpenAM\Exception\OpenAMClientException;
use Dvsa\OpenAM\Model\OpenAMLoginDetails;
use Dvsa\OpenAM\OpenAMAuthProperties;
use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use Zend\Log\LoggerInterface;

/**
 * OpenAMAuthenticator performs authentication requests with OpenAM.
 */
class OpenAMAuthenticator
{
    /**
     * @var OpenAMClientInterface
     */
    private $openAMClient;

    /**
     * @var OpenAMClientOptions
     */
    private $openAMOptions;

    /**
     * @var OpenAMAuthFailureBuilder
     */
    private $authFailureBuilder;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param OpenAMClientInterface    $openAMClient
     * @param OpenAMClientOptions      $openAMOptions
     * @param OpenAMAuthFailureBuilder $authFailureBuilder
     * @param LoggerInterface          $logger
     */
    public function __construct(
        OpenAMClientInterface $openAMClient,
        OpenAMClientOptions $openAMOptions,
        OpenAMAuthFailureBuilder $authFailureBuilder,
        LoggerInterface $logger
    ) {
        $this->openAMClient = $openAMClient;
        $this->openAMOptions = $openAMOptions;
        $this->authFailureBuilder = $authFailureBuilder;
        $this->logger = $logger;
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return OpenAMAuthenticationResponse
     */
    public function authenticate($username, $password)
    {
        if (false === $this->checkCredentials($username, $password)) {
            return $this->authFailureBuilder->createFromCode(OpenAMAuthProperties::CODE_AUTHENTICATION_FAILED);
        }

        $realm = $this->openAMOptions->getRealm();
        $loginDetails = new OpenAMLoginDetails($username, $password, $realm);

        try {
            $this->logger->debug(
                sprintf(
                    'Authenticating user "%s" in realm "%s"',
                    $loginDetails->getUsername(),
                    $loginDetails->getRealm()
                )
            );

            $token = $this->openAMClient->authenticate($loginDetails);
        } catch (OpenAMClientException $e) {
            $this->logger->err($e->getMessage());

            return $this->authFailureBuilder->createAuthFailureFromException($e);
        }

        return new OpenAMAuthSuccess($token);
    }

    /**
     * Performs a basic check for the username and password fields before sending an authentication request to OpenAM.
     *
     * Note that we can't use the Username and Password validators available in the common package as these validators
     * may not be in-sync with OpenDJ and reject a perfectly valid username and password.
     *
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    private function checkCredentials($username, $password)
    {
        return $username && $password;
    }
}
