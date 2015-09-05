<?php

namespace DvsaMotApiTest\Service;

use Aws\S3\S3Client;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotApi\Service\AmazonS3Service;
use PHPUnit_Framework_MockObject_MockObject;
use DvsaMotApi\Service\AmazonSDKService;

/**
 * Unit test for AmazonS3Service
 * Test limited logic within the AmazonS3Service. We do not want to test the
 * SDK functions.
 */
class AmazonS3ServiceTest extends AbstractMotTestServiceTest
{

    private $s3Service;

    public function setUp()
    {
        $config = [
            'aws' => []
        ];

        $config['aws']['URLTimeout'] = '+ 10 Minutes';
        $config['aws']['certificateStorage']['bucket'] = 'bucket1';

        $sdk = XMock::of(AmazonSDKService::class);

        $amazonS3 = new AmazonS3Service($config, $sdk);
        $this->s3Service = $amazonS3;
    }

    /**
     * @throws \DvsaMotApi\Service\NotFoundException
     * @throws \Exception
     * @expectedException \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function testObjectDoesNotExist()
    {
        $config = [
            'aws' => []
        ];

        $config['aws']['URLTimeout'] = '+ 10 Minutes';
        $config['aws']['certificateStorage']['bucket'] = 'bucket1';

        $s3Client = XMock::of(S3Client::class);
        $s3Client->method('doesObjectExist')->willReturn(false);
        $sdk = XMock::of(AmazonSDKService::class);
        $sdk->method('getS3Client')->willReturn($s3Client);

        $amazonS3 = new AmazonS3Service($config, $sdk);
        $amazonS3->getSignedUrlByKey('key');
    }

    /**
     * @throws \DvsaMotApi\Service\NotFoundException
     * @throws \Exception
     * @expectedException Exception
     */
    public function testNoBucketException()
    {
        $config = [
            'aws' => []
        ];

        $config['aws']['URLTimeout'] = '+ 10 Minutes';
        $config['aws']['certificateStorage']['bucket'] = null;

        $s3Client = XMock::of(S3Client::class);
        $s3Client->method('doesObjectExist')->willReturn(true);
        $sdk = XMock::of(AmazonSDKService::class);
        $sdk->method('getS3Client')->willReturn($s3Client);

        $amazonS3 = new AmazonS3Service($config, $sdk);
        $amazonS3->getSignedUrlByKey('key');
    }

    public function testUrlTimeout()
    {
        $config = [
            'aws' => []
        ];

        $config['aws']['URLTimeout'] = '+ 20 Minutes';
        $config['aws']['certificateStorage']['bucket'] = 'bucket1';

        $s3Client = XMock::of(S3Client::class);
        $s3Client->method('doesObjectExist')->willReturn(true);
        $sdk = XMock::of(AmazonSDKService::class);
        $sdk->method('getS3Client')->willReturn($s3Client);

        $amazonS3 = new AmazonS3Service($config, $sdk);

        $class = new \ReflectionClass ($amazonS3);
        $method = $class->getMethod('getUrlTimeout');
        $method->setAccessible(true);

        $this->assertSame(
            $config['aws']['URLTimeout'],
            $method->invoke($amazonS3)
        );
    }

}
