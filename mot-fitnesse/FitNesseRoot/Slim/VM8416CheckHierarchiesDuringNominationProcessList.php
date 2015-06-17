<?php

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\UrlBuilder;

class VM8416CheckHierarchiesDuringNominationProcessList
{

    private $actingUsername;
    private $aeId;

    public function setActingUser($actingUsername)
    {
        $this->actingUsername = $actingUsername;
    }

    public function setTryToGetTheManagementRolesAtOrganisation($organisationId)
    {
        $this->aeId = $organisationId;
    }

    public function andItWillResultIn()
    {

        $url = (new UrlBuilder())->organisationPositionNomination($this->aeId);

        $credential = new CredentialsProvider($this->actingUsername);

        $client = FitMotApiClient::createForCreds($credential);

        try {

            $response = $client->get($url);

            if (is_array($response)) {
                $people = [];
                foreach ($response as $record) {
                    $people[] = $record['person']['id'];
                }
                $response = json_encode($people);
            }

            return print_r($response, true);

        } catch (Exception $e) {

            return sprintf(
                '%s error, code %d',
                $e->getMessage(),
                $e->getCode()
            );

        }
    }
}
