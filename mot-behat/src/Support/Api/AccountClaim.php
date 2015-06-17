<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\Request;

class AccountClaim extends MotApi
{
    const PATH_IDENTITY = 'identity-data';
    const PATH_ACCOUNT_CLAIM = 'account/claim/{user_id}';
    const PATH_PERSON_RESET = 'person/{user_id}/reset-pin';
    const PATH_ACCOUNT_RECLAIM = 'user-admin/user-profile/{user_id}/claim-reset';

    public function getIdentityData($token)
    {
        return $this->client->request(new Request(
            'GET',
            self::PATH_IDENTITY,
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token]
        ));
    }

    public function accountReclaim($token, $userId)
    {
        return $this->client->request(new Request(
            'GET',
            str_replace('{user_id}', $userId, self::PATH_ACCOUNT_RECLAIM),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token]
        ));
    }

    public function updateAccountClaim($token, $userId, $accountClaim)
    {
        $body = json_encode(array_merge($accountClaim, [
            'personId' => $userId,
            'emailConfirmation' => $accountClaim['email'],
            'passwordConfirmation' => $accountClaim['password'],
        ]));

        return $this->client->request(new Request(
            'PUT',
            str_replace('{user_id}', $userId, self::PATH_ACCOUNT_CLAIM),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }
}
