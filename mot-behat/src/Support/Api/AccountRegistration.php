<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\Request;

class AccountRegistration extends MotApi
{
    const PATH = "/account/register";

    /**
     * No token is required for the registration of the user
     * @param array $inputData
     * @return \Dvsa\Mot\Behat\Support\Response
     */
    public function registerUser(array $inputData)
    {
        $body = json_encode($inputData);

        return $this->client->request(new Request(
            MotApi::METHOD_POST,
            self::PATH,
            ['Content-Type' => 'application/json'],
            $body
        ));
    }
}
