<?php

namespace DvsaMotApiTest\Service;

use DvsaMotApi\Service\AwsCredentialsProviderService;
use PHPUnit_Framework_MockObject_MockObject;


class AwsCredentialsProviderServiceTest extends AbstractMotTestServiceTest
{

    public function setUp()
    {
        $config = [
            'aws' => []
        ];

        $config['aws']['URLTimeout'] = '+ 10 Minutes';
        $config['aws']['certificateStorage']['bucket'] = 'bucket1';
    }

    /**
     * @expectedException \Exception
     */
    public function testAccessKeyDoesNotExist()
    {
        $config = [
            'aws' => []
        ];

        $config['aws']['certificateStorage']['accessKeyId'] = null;
        $config['aws']['certificateStorage']['secretKey'] = 'test';

        (new AwsCredentialsProviderService($config))->getAccessKeyId();
    }

    /**
     * @expectedException \Exception
     */
    public function testSecretKeyDoesNotExist()
    {
        $config['aws']['certificateStorage']['accessKeyId'] = 'test';
        $config['aws']['certificateStorage']['secretKey'] = null;

        (new AwsCredentialsProviderService($config))->getSecretKey();
    }

    public function testObjectIsReturned()
    {
        $config['aws']['certificateStorage']['accessKeyId'] = 'test';
        $config['aws']['certificateStorage']['secretKey'] = 'test';

        $provider = new AwsCredentialsProviderService($config);

        $this->assertInstanceOf(AwsCredentialsProviderService::class, $provider);
    }

}
