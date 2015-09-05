<?php

namespace DvsaMotApi\Service;

use Aws\Credentials\CredentialsInterface;
use Aws\Exception\AwsException;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AwsCredentialsProvider
 * @package DvsaMotApi\Service
 */
class AwsCredentialsProviderService implements CredentialsInterface
{
    /**
     * Application configuration array
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(Array $config)
    {
        $this->config = $config;
    }

    /**
     * Returns the access key ID set in the application configuration
     * @return string
     * @throws \Exception
     */
    public function getAccessKeyId()
    {
        if (empty($this->config['aws']['certificateStorage']['accessKeyId'])) {
            throw new \Exception('No AWS access key ID found in configuration');
        }

        return $this->config['aws']['certificateStorage']['accessKeyId'];
    }

    /**
     * Returns the secret key set in the application configuration
     * @return string
     * @throws \Exception
     */
    public function getSecretKey()
    {
        if (empty($this->config['aws']['certificateStorage']['secretKey'])) {
            throw new \Exception('No AWS secret key found in configuration');
        }

        return $this->config['aws']['certificateStorage']['secretKey'];
    }

    public function getSecurityToken()
    {
    }

    public function getExpiration()
    {
    }

    public function isExpired()
    {
    }

    public function toArray()
    {
    }
}
