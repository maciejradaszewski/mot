<?php

namespace DvsaAuthentication\Authentication\Adapter\OpenAM;

use Dvsa\OpenAM\Model\OpenAMExistingIdentity;
use Dvsa\OpenAM\Model\OpenAMLoginDetails;
use Dvsa\OpenAM\Model\OpenAMNewIdentity;
use Dvsa\OpenAM\OpenAMClientInterface;
use Zend\Http\Response;
use Zend\Log\LoggerInterface;

/**
 * Class OpenAMCachedClient
 *
 * Provides cache over getIdentityAttributes method
 *
 * @package Dvsa\OpenAM
 */
class OpenAMCachedClient implements OpenAMClientInterface
{
    private $openAMClient;
    private $cacheProvider;
    private $logger;

    public function __construct(
        OpenAMClientInterface $openAMClient,
        OpenAMIdentityAttributesCacheProvider $cacheProvider,
        LoggerInterface  $logger
    )
    {
        $this->openAMClient = $openAMClient;
        $this->cacheProvider = $cacheProvider;
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function isTokenValid($token)
    {
        $this->openAMClient->isTokenValid($token);
    }

    /**
     * {@inheritDoc}
     */
    public function lockAccount(OpenAMLoginDetails $userDetails)
    {
        $this->openAMClient->lockAccount($userDetails);
    }

    /**
     * {@inheritDoc}
     */
    public function unlockAccount(OpenAMLoginDetails $userDetails)
    {
        $this->openAMClient->unlockAccount($userDetails);
    }

    /**
     * {@inheritDoc}
     */
    public function isAccountLocked(OpenAMLoginDetails $userDetails)
    {
        $this->openAMClient->isAccountLocked($userDetails);
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentityAttributes($subjectId)
    {
        $attributes = $this->cacheProvider->getAttributes($subjectId);

        if (!$attributes) {
            $this->logger->debug("Identity attributes not in cache. Retrieving attributes from OpenAM");
            $attributes = $this->openAMClient->getIdentityAttributes($subjectId);
            $this->cacheProvider->saveAttributes($subjectId, $attributes);
        } else {
            $this->logger->debug("Identity attributes found in cache");
        }

        return $attributes;
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate(OpenAMLoginDetails $loginDetails)
    {
        $this->openAMClient->authenticate($loginDetails);
    }

    /**
     * {@inheritDoc}
     */
    public function logout($token)
    {
        $this->openAMClient->logout($token);
    }

    /**
     * {@inheritDoc}
     */
    public function createIdentity(OpenAMNewIdentity $newIdentity)
    {
        $this->openAMClient->createIdentity($newIdentity);
    }

    /**
     * {@inheritDoc}
     */
    public function updateIdentity(OpenAMExistingIdentity $identity)
    {
        $this->openAMClient->updateIdentity($identity);
    }

    /**
     * {@inheritDoc}
     */
    public function validateCredentials(OpenAMLoginDetails $loginDetails)
    {
        $this->openAMClient->validateCredentials($loginDetails);
    }

    /**
     * {@inheritDoc}
     */
    public function getPasswordExpiryDate(OpenAMLoginDetails $loginDetails)
    {
        $this->openAMClient->getPasswordExpiryDate($loginDetails);
    }
}
