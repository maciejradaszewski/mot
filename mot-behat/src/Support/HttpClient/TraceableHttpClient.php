<?php

namespace Dvsa\Mot\Behat\Support\HttpClient;

use Dvsa\Mot\Behat\Support\HttpClient;
use Dvsa\Mot\Behat\Support\History;
use Dvsa\Mot\Behat\Support\Request;
use Dvsa\Mot\Behat\Support\Response;

class TraceableHttpClient implements HttpClient, History
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var Response[]
     */
    private $responses = [];

    /**
     * @var Response
     */
    private $lastResponse;

    /**
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function request(Request $request)
    {
        return $this->responses[] = $this->lastResponse = $this->client->request($request);
    }

    /**
     * @return Response
     */
    public function getLastResponse()
    {
        if (null === $this->lastResponse) {
            throw new \LogicMethodException('There is no last response as no requests was made yet');
        }

        return $this->lastResponse;
    }

    /**
     * @return Response[]
     */
    public function getAllResponses()
    {
        return $this->responses;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->lastResponse = null;
        $this->responses = [];
    }
}
