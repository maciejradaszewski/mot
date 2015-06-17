<?php

namespace TestSupport\Helper;

use TestSupport\Helper\TestSupportAccessTokenManager;
use TestSupport\Helper\RequestorParserHelper;
use DvsaCommon\HttpRestJson\Client as JsonClient;

class TestSupportRestClientHelper
{

    /**
     * @var JsonClient
     */
    private $jsonClient;

    /**
     * @var bool
     */
    private $prepared = false;

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
        list($schmUsername, $schmPassword) = RequestorParserHelper::parse($data);

        $accessToken = $this->accessTokenManager->getToken($schmUsername, $schmPassword);
        $this->jsonClient->setAccessToken($accessToken);
    }

    public function getJsonClient($data)
    {
        if(!$this->prepared) {
            $this->prepare($data);
            $this->prepared = true;
        }
        return $this->jsonClient;
    }

}
