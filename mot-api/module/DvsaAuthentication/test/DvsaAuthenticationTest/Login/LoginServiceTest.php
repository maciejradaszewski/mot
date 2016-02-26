<?php

namespace DvsaAuthenticationTest\Login;

use DvsaAuthentication\Login\AuthenticationResponseMapper;
use DvsaAuthentication\Login\LoginService;
use DvsaAuthentication\Login\Response\GenericAuthenticationFailure;
use DvsaAuthentication\Login\UsernamePasswordAuthenticator;
use DvsaCommon\Dto\Authn\AuthenticationResponseDto;
use DvsaCommonTest\TestUtils\MethodSpy;
use DvsaCommonTest\TestUtils\XMock;
use PersonApi\Service\PasswordExpiryService;

class LoginServiceTest extends \PHPUnit_Framework_TestCase
{

    private $passwordExpiryService;

    private $mapper;

    private $authenticator;


    public function setUp() {

        $this->passwordExpiryService = XMock::of(PasswordExpiryService::class);
        $this->mapper = XMock::of(AuthenticationResponseMapper::class);
        $this->authenticator = XMock::of(UsernamePasswordAuthenticator::class);
    }


    private function createService() {

        return new LoginService($this->authenticator, $this->passwordExpiryService, $this->mapper);
    }

    public function testLogin() {

        $response = new GenericAuthenticationFailure('anyMessage');
        $this->authenticator->expects($this->once())->method('authenticate')
            ->willReturn($response);

        $authenticate = new MethodSpy($this->authenticator, 'authenticate');
        $mapToDto = new MethodSpy($this->mapper, 'mapToDto');

        $this->createService()->login('customUsername', 'customPassword');

        $this->assertEquals('customUsername', $authenticate->paramsForInvocation(0)[0]);
        $this->assertEquals('customPassword', $authenticate->paramsForInvocation(0)[1]);

        $this->assertEquals($response, $mapToDto->paramsForInvocation(0)[0]);
        $this->assertEquals('customUsername', $mapToDto->paramsForInvocation(0)[1]);
    }


}