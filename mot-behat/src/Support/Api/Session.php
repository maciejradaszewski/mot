<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\Api\Session\AuthenticatedUser;
use Dvsa\Mot\Behat\Support\Request;
use Dvsa\Mot\Behat\Support\Helper\TestSupportHelper;
use Exception;

class Session extends MotApi
{
    const PATH = 'session';

    /**
     * @param string $username
     * @param string $password
     *
     * @return AuthenticatedUser
     */
    public function startSession($username, $password)
    {
        $response = $this->createNewSession($username, $password);

        if (!isset($response->getBody()['data']['accessToken'])) {
            throw new Exception(sprintf('No access Token returned with User Credentials: %s / %s', $username, $password));
        }

        return new AuthenticatedUser(
            $response->getBody()['data']['user']['userId'],
            $response->getBody()['data']['user']['username'],
            $response->getBody()['data']['accessToken']
        );
    }

    /**
     * @param string $username
     * @param string $password
     */
    private function createNewSession($username, $password)
    {
        $body = json_encode([
            'username' => $username,
            'password' => $password,
        ]);

        return $this->client->request(new Request(
            'POST',
            self::PATH,
            ['Content-Type' => 'application/json'],
            $body
        ));
    }

    /**
     * High level function to allow logging in as a tester
     * @param TestSupportHelper $helper
     * @return string Returned token
     */
    public function logInAsTester(TestSupportHelper $helper)
    {
        $testerService = $helper->getTesterService();
        $tester = $testerService->create([
            'siteIds' => [1],
        ]);

        return $this->startSession($tester->data['username'], $tester->data['password']);
    }
}
