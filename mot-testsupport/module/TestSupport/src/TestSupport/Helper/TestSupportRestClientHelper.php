<?php

namespace TestSupport\Helper;

use DvsaCommon\HttpRestJson\Client as JsonClient;

class TestSupportRestClientHelper
{

    /**
     * @var JsonClient
     */
    private $jsonClient;

    /**
     * @var array
     */
    private $requestors = [];

    /**
     * @var TestSupportAccessTokenManager
     */
    private $accessTokenManager;

    public function __construct(JsonClient $jsonClient, TestSupportAccessTokenManager $accessTokenManager)
    {
        $this->jsonClient = $jsonClient;
        $this->accessTokenManager = $accessTokenManager;
    }

    public function prepare(array $data)
    {
        if (!isset($data["requestor"])) {
            $data["requestor"] = ["username" => "schememgt", "password" => "Password1"];
        }

        list($schmUsername, $schmPassword) = RequestorParserHelper::parse($data);

        if (in_array($schmUsername, $this->requestors)) {
            return;
        }

        $this->requestors[] = $schmUsername;
        $accessToken = $this->accessTokenManager->getToken($schmUsername, $schmPassword);
        $this->jsonClient->setAccessToken($accessToken);
    }

    public function getJsonClient($data)
    {
        $this->prepare($data);

        return $this->jsonClient;
    }
}

