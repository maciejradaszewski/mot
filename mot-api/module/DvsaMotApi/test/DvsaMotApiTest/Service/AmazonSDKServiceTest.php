<?php

namespace DvsaMotApiTest\Service;

use Aws\S3\S3Client;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotApi\Service\AmazonSDKService;
use PHPUnit_Framework_MockObject_MockObject;
use DvsaMotApi\Service\AwsCredentialsProviderService;

/**
 * Unit test for AmazonSDKService
 */
class AmazonSDKServiceTest extends AbstractMotTestServiceTest
{

    public function testSDKObjectIsReturned()
    {
        $config = [];
        $config['aws']['certificateStorage']['region'] = 'Europe';

        $credentials = XMock::of(AwsCredentialsProviderService::class);
        $credentials->method('getAccessKeyId')->willReturn('key');
        $credentials->method('getAccessKeyId')->willReturn('secret');

        $amazonSDK = new AmazonSDKService($config, $credentials);
        $this->assertInstanceOf(AmazonSDKService::class, $amazonSDK);
    }

    public function testS3ClientIsReturned()
    {
        $config = [];
        $config['aws']['certificateStorage']['region'] = 'Europe';

        $credentials = XMock::of(AwsCredentialsProviderService::class);
        $credentials->method('getAccessKeyId')->willReturn('key');
        $credentials->method('getAccessKeyId')->willReturn('secret');

        $amazonSDK = new AmazonSDKService($config, $credentials);
        $this->assertInstanceOf(S3Client::class, $amazonSDK->getS3Client());
    }

    /**
     * @expectedException Exception
     */
    public function testNoRegionException()
    {
        $config = [];
        $config['aws']['certificateStorage']['region'] = null;

        $credentials = XMock::of(AwsCredentialsProviderService::class);
        $credentials->method('getAccessKeyId')->willReturn('key');
        $credentials->method('getAccessKeyId')->willReturn('secret');

        $amazonSDK = new AmazonSDKService($config, $credentials);
        $amazonSDK->getS3Client();
    }

}
