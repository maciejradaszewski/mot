<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\HttpClient;
use Dvsa\Mot\Behat\Support\Request;

class MotApi
{
    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_GET    = 'GET';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PATCH   = 'PATCH';

    protected $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $token
     * @param string $method
     * @param string $path
     * @param array  $params
     *
     * @return \Dvsa\Mot\Behat\Support\Response
     */
    public function sendRequest($token, $method, $path, array $params = null)
    {
        $body = null !== $params ? json_encode($params) : null;

        return $this->client->request(new Request(
            $method,
            $path,
            [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ],
            $body
        ));
    }
}
