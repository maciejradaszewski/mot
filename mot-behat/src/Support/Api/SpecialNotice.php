<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\Request;

class SpecialNotice extends MotApi
{
    const PATH_SN_CONTENT = 'special-notice-content';
    const PATH = 'special-notice';
    const PATH_SN_BROADCAST = 'special-notice-broadcast';
    const PATH_SN_PUBLISH = 'special-notice-content/{sn_id}/publish';
    const PATH_SN_PERSON = 'person/{person_id}/special-notice';

    public function sendBroadcast($token)
    {
        $result = $this->client->request(new Request(
            'POST',
            self::PATH_SN_BROADCAST,
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token]
        ));

        return (isset($result->getBody()['data']['success'])) ? $result->getBody()['data']['success'] : false;
    }

    /**
     * @param $token
     * @param array $data
     *
     * @return Response
     */
    public function createSpecialNotice($token, array $data = [])
    {
        $default = [
            'noticeTitle' => 'Special Notice title',
            'internalPublishDate' => (new \DateTime())->format("Y-m-d"),
            'externalPublishDate' => (new \DateTime("tomorrow"))->format("Y-m-d"),
            'acknowledgementPeriod' => '1',
            'noticeText' => 'upper',
            'targetRoles' => ['DVSA', "VTS"],
        ];

        $default = array_replace($default, $data);
        $body = json_encode($default);

        return $this->client->request(new Request(
            'POST',
            self::PATH_SN_CONTENT,
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }

    public function publish($token, $id)
    {
        return $this->client->request(new Request(
            'PUT',
            str_replace("{sn_id}", $id, self::PATH_SN_PUBLISH),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token]
        ));
    }

    public function getSpecialNotices($token, $personId)
    {
        return $this->client->request(new Request(
            'GET',
            str_replace("{person_id}", $personId, self::PATH_SN_PERSON),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token]
        ));
    }
}
