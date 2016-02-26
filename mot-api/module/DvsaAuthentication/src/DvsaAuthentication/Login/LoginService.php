<?php

namespace DvsaAuthentication\Login;

use DvsaAuthentication\IdentityFactory;
use DvsaCommon\Dto\Authn\AuthenticationResponseDto;
use PersonApi\Service\PasswordExpiryService;
use Zend\Authentication\Result;

/**
 * Application service to perform all the logic relevant to login event
 */
class LoginService
{
    private $authenticator;

    private $passwordExpiryService;

    private $mapper;

    public function __construct(
        UsernamePasswordAuthenticator $authenticator,
        PasswordExpiryService $passwordExpiryService,
        AuthenticationResponseMapper $mapper
    ) {
        $this->authenticator = $authenticator;
        $this->passwordExpiryService = $passwordExpiryService;
        $this->mapper = $mapper;
    }

    /**
     * @param $username
     * @param $password
     * @return AuthenticationResponseDto
     */
    public function login($username, $password)
    {
        $authResponse = $this->authenticator->authenticate($username, $password);
        $identityData = $this->mapper->mapToDto($authResponse, $username);

        return $identityData;
    }


}

