<?php

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\UrlBuilder;

class VM8416CheckHierarchiesDuringNominationProcessRemove
{

    private $actingUsername;
    private $targetUserId;
    private $aeId;
    private $positionId;

    public function setActingUser($actingUsername)
    {
        $this->actingUsername = $actingUsername;
    }

    public function setTryToDisassociateUserById($targetUserId)
    {
        $this->targetUserId = $targetUserId;
    }

    public function setComment($comment)
    {

    }

    public function setAt($organisationId)
    {
        $this->aeId = $organisationId;
    }

    public function setPositionId($positionId)
    {
        $this->positionId = $positionId;
    }

    public function andItWillResultIn()
    {
        $url = (new UrlBuilder())->organisationPositionNomination($this->aeId, $this->positionId);

        $credential = new CredentialsProvider($this->actingUsername);

        $client = FitMotApiClient::createForCreds($credential);

        try {

            $response = $client->delete($url);

            if (is_array($response)) {
                $response = json_encode($response);
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
