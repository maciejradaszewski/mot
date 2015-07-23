<?php

namespace DvsaAuthentication\Authentication\Service;

use DvsaApplicationLogger\TokenService\TokenServiceInterface;
use DvsaCommon\Auth\Http\AuthorizationBearer;
use InvalidArgumentException;

class ApiTokenService implements TokenServiceInterface
{

    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function getToken()
    {

        return $this->parseToken();
    }

    public function parseToken()
    {
        $token = null;
        $header = $this->request->getHeader(AuthorizationBearer::FIELD_NAME);
        if ($header) {
            try {
                $authHeader = AuthorizationBearer::fromString($header->toString());
                $token = $authHeader->getToken();
            } catch (InvalidArgumentException $e) {
            }
        }
        return $token;
    }

}