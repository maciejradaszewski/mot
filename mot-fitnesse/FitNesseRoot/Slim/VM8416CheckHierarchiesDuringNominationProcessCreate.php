<?php

use DvsaCommon\Enum\OrganisationBusinessRoleId;
use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\UrlBuilder;

class VM8416CheckHierarchiesDuringNominationProcessCreate
{

    private $nominatorUsername;
    private $nominateId;
    private $nominateUsername;
    private $roleId;
    private $positionId;
    private $aeId;

    public function setNominator($nominatorUsername)
    {
        $this->nominatorUsername = $nominatorUsername;
    }

    public function setNominate($NomineeId)
    {
        $this->nominateId = $NomineeId;
    }

    public function setUsername($username) {
        $this->nominateUsername = $username;
    }

    public function setComment($comment)
    {

    }

    public function setToBe($role)
    {
        switch ($role) {
            case 'AED':
                $this->roleId = OrganisationBusinessRoleId::AUTHORISED_EXAMINER_DELEGATE;
                break;
            case 'AEDM':
                $this->roleId = OrganisationBusinessRoleId::AUTHORISED_EXAMINER_DESIGNATED_MANAGER;
                break;
            default;
                throw new Exception(
                    '"To be" column only can set to AED for "Authorised Examiner Delegate"' .
                    'or AEDM for "Authorised Examiner Designated Manager"'
                );
        }
    }

    public function setAt($organisationId)
    {
        $this->aeId = $organisationId;
    }

    public function positionId()
    {
        return $this->positionId;
    }

    public function andItWillResultIn()
    {
        $url = (new UrlBuilder())->organisationPositionNomination($this->aeId);

        $data = [
            'nomineeId' => $this->nominateId,
            'roleId' => $this->roleId
        ];

        $credential = new CredentialsProvider($this->nominatorUsername);
        $client = FitMotApiClient::createForCreds($credential);

        $nominateCredential = new CredentialsProvider($this->nominateUsername);
        $nominateClient = FitMotApiClient::createForCreds($nominateCredential);

        try {
            $response = $client->post($url, $data);

            if (is_array($response)) {

                if (array_key_exists('id', $response)) {
                    $this->positionId = $response['id'];
                }

                // Get all the nominees notifications
                $notificationUrl = (new UrlBuilder())->notificationForPerson($this->nominateId);
                $personNotifications = $nominateClient->get($notificationUrl);

                // Look for the one with nominationId = positionId
                $notification = null;
                foreach ($personNotifications as $personNotification) {
                    if ($personNotification['fields']['nominationId'] == $this->positionId) {
                        $notification = $personNotification;
                        continue;
                    }
                }

                // Lets accept this nomination to activate the OrganisationBusinessRole
                // Used a string for the action as there is no Constant available in MOT FITNESSE or MOT COMMON
                $data = [
                    'action' => 'ORGANISATION-NOMINATION-ACCEPTED',
                ];

                $notificationUrl = (new UrlBuilder())->notification($notification['id'])
                                                     ->action();

                $nominateClient->put($notificationUrl, $data);

                // Return Response
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
