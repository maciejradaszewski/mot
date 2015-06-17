<?php

use DvsaCommon\Dto\Account\MessageDto;
use DvsaCommon\Utility\DtoHydrator;
use MotFitnesse\Util\AccountUrlBuilder;
use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\TestShared;

class Helpdesk_Vm8713RegisteredUserChangesPassword
{
    protected $token;
    protected $response;

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function tokenValid()
    {
        $client = FitMotApiClient::createForCreds(
            new CredentialsProvider('csco', TestShared::PASSWORD)
        );

        $url = AccountUrlBuilder::resetPassword($this->token);

        try {
            return (DtoHydrator::jsonToDto($client->get($url)) instanceof MessageDto);
        } catch (ApiErrorException $e) {
            return false;
        } catch (\Exception $e) {
            return 'error';
        }
    }
}
