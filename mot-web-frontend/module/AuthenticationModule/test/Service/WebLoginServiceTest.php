<?php

namespace Dvsa\Mot\Frontend\AuthenticationModuleTest\Service;

use Dvsa\Mot\Frontend\AuthenticationModule\Model\Identity;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\WebLoginService;
use DvsaCommon\Authn\AuthenticationResultCode;
use DvsaCommon\Dto\Authn\AuthenticatedUserDto;
use DvsaCommon\Dto\Authn\AuthenticationResponseDto;
use DvsaCommon\DtoSerialization\DtoReflectiveDeserializer;
use DvsaCommon\HttpRestJson\Client;
use DvsaCommonTest\TestUtils\XMock;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\NonPersistent;

class WebLoginServiceTest extends \PHPUnit_Framework_TestCase
{

    private $authenticationService;
    private $client;
    private $deserializer;

    public function setUp()
    {
        $this->authenticationService = (new AuthenticationService())->setStorage(new NonPersistent());
        $this->client = XMock::of(Client::class);
        $this->deserializer = XMock::of(DtoReflectiveDeserializer::class);
    }

    public function testLogin_authenticationSuccessful()
    {
        $authenticationDto = (new AuthenticationResponseDto())
            ->setAuthnCode(AuthenticationResultCode::SUCCESS)
            ->setExtra(null)
            ->setUser((new AuthenticatedUserDto())
                ->setDisplayName('displayName')
                ->setUserId(5)
                ->setUsername('customUsername')
                ->setIsAccountClaimRequired(true)
                ->setIsSecondFactorRequired(true)
                ->setIsPasswordChangeRequired(true));

        $user = $authenticationDto->getUser();

        $this->client->expects($this->once())->method('post')
            ->willReturn(['data' => []]);

        $this->deserializer->expects($this->once())->method('deserialize')
            ->willReturn($authenticationDto);

        $returnedAuthenticationDto = $this->create()->login('username', 'password');

        $this->assertEquals(AuthenticationResultCode::SUCCESS, $returnedAuthenticationDto->getAuthnCode());
        /** @var Identity $identity */
        $identity = $this->authenticationService->getStorage()->read();

        $this->assertEquals($user->getUsername(), $identity->getUsername());
        $this->assertEquals($user->getDisplayName(), $identity->getDisplayName());
        $this->assertEquals($user->getUserId(), $identity->getUserId());
        $this->assertEquals($user->isIsSecondFactorRequired(), $identity->isSecondFactorRequired());
        $this->assertEquals($user->isIsAccountClaimRequired(), $identity->isAccountClaimRequired());
        $this->assertEquals($user->isIsPasswordChangeRequired(), $identity->isPasswordChangeRequired());
        $this->assertEquals($authenticationDto->getAccessToken(), $identity->getAccessToken());

    }

    public function testLogin_authenticationFailed()
    {
        $authenticationDto = (new AuthenticationResponseDto())
            ->setAuthnCode(AuthenticationResultCode::INVALID_CREDENTIALS)
            ->setExtra(null)
            ->setUser(null);

        $this->client->expects($this->once())->method('post')
            ->willReturn(['data' => []]);

        $this->deserializer->expects($this->once())->method('deserialize')
            ->willReturn($authenticationDto);

        $returnedAuthenticationDto = $this->create()->login('username', 'password');

        $this->assertEquals(AuthenticationResultCode::INVALID_CREDENTIALS, $returnedAuthenticationDto->getAuthnCode());
        /** @var Identity $identity */
        $identity = $this->authenticationService->getStorage()->read();

        $this->assertNull($identity);
    }

    private function create()
    {
        return new WebLoginService($this->authenticationService, $this->client, $this->deserializer);
    }

}