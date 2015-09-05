<?php

/**
 * Return configured S3 object
 */
namespace DvsaMotApi\Service;

use Aws\S3\S3Client;

/**
 * Class AmazonSDKService
 *
 * @package DvsaMotApi\Service
 */
class AmazonSDKService
{
    private $s3Client;
    private $AWSCredentials;
    private $region = null;

    /**
     * @param array $config
     * @param AwsCredentialsProviderService $awsCredentialsProviderService
     * @throws \Exception
     */
    public function __construct(Array $config, AwsCredentialsProviderService $awsCredentialsProviderService)
    {
        $this->AWSCredentials = $awsCredentialsProviderService;

        if (isset($config['aws']['certificateStorage']['region'])) {
            $this->region = $config['aws']['certificateStorage']['region'];
        }
    }

    /**
     * @return S3Client
     * @throws \Exception
     */
    public function getS3Client()
    {
        if ($this->s3Client instanceof S3Client){
            return $this->s3Client;
        }

        if (null === $this->region) {
            throw new \Exception("No region found for AWS within system configuration");
        }

        $this->s3Client = new S3Client([
            'region' => $this->region,
            'version' => 'latest',
            'credentials' => $this->AWSCredentials,
        ]);

        return $this->s3Client;
    }

}
