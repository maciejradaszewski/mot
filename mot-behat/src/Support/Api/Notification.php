<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\HttpClient;

class Notification extends MotApi
{
    const PATH = '/notification/person/';
    const PATH_NOTIFICATION_ACTION = '/notification/{notification_id}/action';

    /**
     * @param string $token
     * @param int $personId
     * @return \Dvsa\Mot\Behat\Support\Response
     */
    public function fetchNotificationForPerson($token, $personId)
    {
        return $this->sendRequest(
            $token,
            MotApi::METHOD_GET,
            self::PATH.$personId
        );
    }

    public function getRoleNominationNotification($role, $userId, $token)
    {
        $response = $this->fetchNotificationForPerson(
            $token,
            $userId
        );

        $notifications = $response->getBody()->toArray();

        foreach ($notifications['data'] as $notification) {
            if ($notification['fields']['positionName'] === $role) {
                return $notification;
            }
        }

        throw new \InvalidArgumentException("Notification not found");
    }

    public function acceptSiteNomination($token, $notificationId)
    {
        $data = [
            "action" => "SITE-NOMINATION-ACCEPTED",
        ];

        return $this->acceptNomination($token, $notificationId, $data);
    }

    public function acceptOrganisationNomination($token, $notificationId)
    {
        $data = [
            "action" => "ORGANISATION-NOMINATION-ACCEPTED",
        ];

        return $this->acceptNomination($token, $notificationId, $data);
    }

    private function acceptNomination($token, $notificationId, array $data)
    {
        return $this->sendRequest(
            $token,
            MotApi::METHOD_PUT,
            str_replace("{notification_id}", $notificationId, self::PATH_NOTIFICATION_ACTION),
            $data
        );
    }
}
