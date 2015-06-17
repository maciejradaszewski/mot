<?php

namespace DvsaAuthentication\Service;

use DvsaApplicationLogger\TokenService\TokenServiceInterface;
use Zend\Http\PhpEnvironment\Request;

class WebAccessTokenService implements TokenServiceInterface
{
    private $request;
    private $openAMConfig;

    public function __construct(Request $request, $openAMConfig)
    {
        $this->request = $request;
        $this->openAMConfig = $openAMConfig;
    }

    public function getToken()
    {
        $openAMCookieName = $this->openAMConfig['cookie_name'];
        $cookies = $this->request->getCookie();
        return isset($cookies[$openAMCookieName]) ? $cookies[$openAMCookieName] : null;
    }
}
