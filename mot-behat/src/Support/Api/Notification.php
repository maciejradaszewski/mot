<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\HttpClient;
use Dvsa\Mot\Behat\Support\Request;

class Notification {

    const PATH = '/notification/person/';

    /**
     * @var HttpClient
     */
    private $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $token
     * @param int $personId
     * @return \Dvsa\Mot\Behat\Support\Response
     */
    public function fetchNotificationForPerson($token, $personId)
    {
        return $this->client->request(new Request(
            MotApi::METHOD_GET,
            self::PATH.$personId,
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token]
        ));
    }
}