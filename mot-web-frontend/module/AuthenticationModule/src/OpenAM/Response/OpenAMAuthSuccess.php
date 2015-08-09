<?php

namespace Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response;

/**
 * OpenAMAuthSuccess represents a successful OpenAM authentication attempt.
 */
class OpenAMAuthSuccess implements OpenAMAuthenticationResponse
{
    /**
     * @var string
     */
    private $token;

    /**
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccess()
    {
        return true;
    }
}
