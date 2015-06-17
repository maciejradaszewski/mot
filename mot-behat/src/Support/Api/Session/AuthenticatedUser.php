<?php

namespace Dvsa\Mot\Behat\Support\Api\Session;

class AuthenticatedUser
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @param string $userId
     * @param string $username
     * @param string $accessToken
     */
    public function __construct($userId, $username, $accessToken)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->accessToken = $accessToken;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
}