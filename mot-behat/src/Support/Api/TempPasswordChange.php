<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\Request;
use Dvsa\Mot\Behat\Support\Helper\TestSupportHelper;

class TempPasswordChange extends MotApi
{
    const PATH_PASSWORD_UPDATE = 'account/password-update/{user_id}';

    /**
     * @var TestSupportHelper
     */
    private $testSupportHelper;

    /**
     * @param TestSupportHelper $testSupportHelper
     */
    public function setTestSupportHelper(TestSupportHelper $testSupportHelper)
    {
        $this->testSupportHelper = $testSupportHelper;
    }

    /**
     * @param string $token
     * @param int $userId
     * @param string $password
     * @return \Dvsa\Mot\Behat\Support\Response
     */
    public function updatePassword($token, $userId, $password)
    {
        $body = json_encode(
            [
                'password' => $this->testSupportHelper->getParamObfuscatorService()->obfuscate($password)
            ]
        );

        return $this->client->request(new Request(
            'PUT',
            str_replace('{user_id}', $userId, self::PATH_PASSWORD_UPDATE),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }
}
