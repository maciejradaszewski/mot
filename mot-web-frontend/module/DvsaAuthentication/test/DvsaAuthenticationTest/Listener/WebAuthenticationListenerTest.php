<?php


namespace DvsaAuthenticationTest\Listener;

use DvsaApplicationLogger\TokenService\TokenServiceInterface;
use DvsaAuthentication\Listener\WebAuthenticationListener;
use DvsaCommonTest\TestUtils\XMock;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;
use Zend\Log\LoggerInterface;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\Stdlib\Parameters;
use Zend\Mvc\MvcEvent;
use Zend\Uri\Uri;
use DvsaAuthentication\Model\Identity;

/**
 * Class WebAuthenticationListenerTest
 * @package DvsaAuthenticationTest\Listener
 */
class WebAuthenticationListenerTest extends \PHPUnit_Framework_TestCase
{
    /** @var TokenServiceInterface */
    private $tokenService;
    /** @var AuthenticationService */
    private $authService;
    private $config;

    /** @var WebAuthenticationListener */
    private $service;
    private $event;
    private $request;
    private $uri;
    private $identity;
    private $logger;

    public function setUp()
    {
        $this->event = XMock::of(MvcEvent::class);
        $this->request = XMock::of(Request::class);
        $this->uri = XMock::of(Uri::class);

        $this->authService = XMock::of(AuthenticationService::class);
        $this->tokenService = XMock::of(TokenServiceInterface::class);
        $this->identity = XMock::of(Identity::class);
        $this->logger = XMock::of(LoggerInterface::class);
    }

    public function testInvokeMethod()
    {
        $this->config = [
            'dvsa_authentication' => [
                'whiteList' => ['/^test$/']
            ]
        ];

        $this->service = new WebAuthenticationListener($this->authService, $this->tokenService, $this->logger, $this->config);

        $this->event->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->request);
        $this->request->expects($this->once())
            ->method('getUri')
            ->willReturn($this->uri);
        $this->uri->expects($this->once())
            ->method('getPath')
            ->willReturn('test');

        $this->assertNull($this->service->__invoke($this->event));
    }

    public function testInvokeMethodWrongRegex()
    {
        $this->config = [
            'dvsa_authentication' => [
                'whiteList' => ['wrongRegex']
            ]
        ];

        $this->service = new WebAuthenticationListener($this->authService, $this->tokenService, $this->logger, $this->config);

        $this->event->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->request);
        $this->request->expects($this->once())
            ->method('getUri')
            ->willReturn($this->uri);
        $this->uri->expects($this->once())
            ->method('getPath')
            ->willReturn('test');

        $this->authService->expects($this->at(0))
            ->method('hasIdentity')
            ->willReturn(true);
        $this->authService->expects($this->at(1))
            ->method('getIdentity')
            ->willReturn($this->identity);

        $this->identity->expects($this->at(0))
            ->method('getAccessToken')
            ->willReturn('');
        $this->tokenService->expects($this->at(0))
            ->method('getToken')
            ->willReturn('');

        $this->assertNull($this->service->__invoke($this->event));
    }
}
