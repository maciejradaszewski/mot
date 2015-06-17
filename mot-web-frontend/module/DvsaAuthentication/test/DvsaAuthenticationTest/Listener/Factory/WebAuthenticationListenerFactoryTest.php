<?php

namespace DvsaAuthenticationTest\Listener\Factory;

use DvsaAuthentication\Listener\Factory\WebAuthenticationListenerFactory;
use DvsaAuthentication\Listener\WebAuthenticationListener;
use DvsaCommonTest\TestUtils\XMock;
use Zend\Log\LoggerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaApplicationLogger\TokenService\TokenServiceInterface;
use Zend\Authentication\AuthenticationService;

/**
 * Class WebAuthenticationListenerFactoryTest
 * @package DvsaAuthenticationTest\Listener\Factory
 */
class WebAuthenticationListenerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /* @var WebAuthenticationListenerFactory $securityServiceFactory */
    private $serviceFactory;

    private $serviceLocatorMock;
    private $tokenService;
    private $zendAuthenticationService;
    private $config;
    private $logger;

    public function setUp()
    {
        $this->serviceFactory = new WebAuthenticationListenerFactory();
        $this->serviceLocatorMock = XMock::of(ServiceLocatorInterface::class, ['get']);

        $this->zendAuthenticationService = XMock::of(AuthenticationService::class);
        $this->tokenService = XMock::of(TokenServiceInterface::class);
        $this->logger = XMock::of(LoggerInterface::class);

        $this->config = [];
    }

    public function testEventServiceGetList()
    {
        $this->serviceLocatorMock->expects($this->at(0))
            ->method('get')
            ->willReturn($this->zendAuthenticationService);
        $this->serviceLocatorMock->expects($this->at(1))
            ->method('get')
            ->willReturn($this->tokenService);
        $this->serviceLocatorMock->expects($this->at(2))
            ->method('get')
            ->willReturn($this->config);
        $this->serviceLocatorMock->expects($this->at(3))
            ->method('get')
            ->willReturn($this->logger);

        $this->assertInstanceOf(
            WebAuthenticationListener::class,
            $this->serviceFactory->createService($this->serviceLocatorMock)
        );
    }
}
