<?php

namespace DvsaMotApiTest\Factory;

use Aws\S3\S3Client;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotApi\Factory\AmazonS3ServiceFactory;
use DvsaMotApi\Service\AmazonS3Service;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaMotApi\Service\AmazonSDKService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceManager;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;


class AmazonS3ServiceFactoryTest extends AbstractServiceTestCase
{

    private $serviceLocator;

    public function setUp()
    {
        $config = [
            'aws' => []
        ];

        $config['aws']['URLTimeout'] = '+ 10 Minutes';
        $config['aws']['certificateStorage']['bucket'] = 'bucket1';

        $amazonSDK = XMock::of(AmazonSDKService::class);

        $this->serviceLocator = new ServiceManager();
        $this->serviceLocator->setService('Config', $config);
        $this->serviceLocator->setService(AmazonSDKService::class, $amazonSDK);
    }

    public function testFactory()
    {
        $service = (new AmazonS3ServiceFactory())->createService($this->serviceLocator);

        $this->assertInstanceOf(
            AmazonS3Service::class,
            $service
        );
    }
}
