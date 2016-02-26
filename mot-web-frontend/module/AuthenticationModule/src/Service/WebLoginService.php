<?php
namespace Dvsa\Mot\Frontend\AuthenticationModule\Service;

use Dvsa\Mot\Frontend\AuthenticationModule\Model\Identity;
use DvsaCommon\Authn\AuthenticationResultCode;
use DvsaCommon\Dto\Authn\AuthenticationResponseDto;
use DvsaCommon\DtoSerialization\DtoReflectiveDeserializer;
use DvsaCommon\UrlBuilder\UrlBuilder;
use Zend\Authentication\AuthenticationService;
use DvsaCommon\HttpRestJson\Client;

class WebLoginService
{
    private $authenticationService;

    private $client;

    private $deserializer;

    public function __construct(
        AuthenticationService $authenticationService,
        Client $client,
        DtoReflectiveDeserializer $deserializer
    ) {
        $this->authenticationService = $authenticationService;
        $this->client = $client;
        $this->deserializer = $deserializer;
    }

    /**
     * @param $username
     * @param $password
     * @return \Zend\Authentication\Result
     */
    public function login($username, $password)
    {
        $restResult = $this->client->post((new UrlBuilder())->session()->toString(),
            [ 'username' => $username, 'password' => $password]
        );
        /** @var AuthenticationResponseDto $responseDto */
        $responseDto = $this->deserializer->deserialize($restResult['data'], AuthenticationResponseDto::class);

        $authenticationCode = $responseDto->getAuthnCode();
        if ($authenticationCode === AuthenticationResultCode::SUCCESS) {
            $identity = (new Identity())
                ->setUserId($responseDto->getUser()->getUserId())
                ->setUsername($responseDto->getUser()->getUsername())
                ->setDisplayName($responseDto->getUser()->getDisplayName())
                ->setDisplayRole($responseDto->getUser()->getRole())
                ->setAccessToken($responseDto->getAccessToken())
                ->setAccountClaimRequired($responseDto->getUser()->isIsAccountClaimRequired())
                ->setPasswordChangeRequired($responseDto->getUser()->isIsPasswordChangeRequired())
                ->setSecondFactorRequired($responseDto->getUser()->isIsSecondFactorRequired());
            $this->authenticationService->getStorage()->write($identity);
        }
        return $responseDto;
    }
}