<?php

namespace DvsaMCommonTest\Factory\Validator;

use Doctrine\Common\Cache\Cache;
use DvsaApplicationLogger\TokenService\TokenServiceInterface;
use DvsaCommon\Factory\HttpRestJson\ClientFactory;
use DvsaCommon\HttpRestJson\CachingClient;
use DvsaCommon\HttpRestJson\ZendClient;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceManager;

class ClientFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testItIsAZendSerivceManagerFactory()
    {
        $this->assertInstanceOf(FactoryInterface::class, new ClientFactory());
    }

    public function testItCreatesTheZendClientByDefault()
    {
        $serviceManager = $this->getServiceManager(['apiUrl' => 'localhost']);

        $factory = new ClientFactory();

        $this->assertInstanceOf(ZendClient::class, $factory->createService($serviceManager));
    }

    public function testItCreatesTheCachingClientIfEnabled()
    {
        $serviceManager = $this->getServiceManager([
            'apiUrl' => 'localhost',
            'rest_client' => [
                'cache' => [
                    'enabled' => true
                ]
            ]
        ]);
        $serviceManager->expects($this->at(2))
            ->method('get')
            ->with(Cache::class)
            ->willReturn($this->getCache());

        $factory = new ClientFactory();

        $this->assertInstanceOf(CachingClient::class, $factory->createService($serviceManager));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getServiceManager(array $config)
    {
        $serviceManager = $this->getMock(ServiceManager::class);
        $serviceManager->expects($this->at(0))
            ->method('get')
            ->with('config')
            ->willReturn($config);
        $serviceManager->expects($this->at(1))
            ->method('get')
            ->with('tokenService')
            ->willReturn($this->getTokenService());

        return $serviceManager;
    }

    private function getTokenService()
    {
        return $this->getMock(TokenServiceInterface::class, ['getToken']);
    }

    private function getCache()
    {
        return $this->getMock(Cache::class);
    }
}