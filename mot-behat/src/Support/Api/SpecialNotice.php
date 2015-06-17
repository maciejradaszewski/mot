<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\Request;

class SpecialNotice extends MotApi
{
    const PATH_SN_CONTENT = 'special-notice-content';
    const PATH = 'special-notice';
    const PATH_SN_BROADCAST = 'special-notice-broadcast';

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
     *
     * @return Response
     */
    public function createSpecialNotice($token)
    {
        $internalPublishDate = new \DateTime("tomorrow");
        $externalPublishDate = new \DateTime("tomorrow + 1day");

        $body = json_encode([
            'noticeTitle' => 'Special Notice title',
            'internalPublishDate' => $internalPublishDate->format("Y-m-d"),
            'externalPublishDate' => $externalPublishDate->format("Y-m-d"),
            'acknowledgementPeriod' => '1',
            'noticeText' => 'upper',
            'targetRoles' => ['TESTER-CLASS-1'],
        ]);

        return $this->client->request(new Request(
            'POST',
            self::PATH_SN_CONTENT,
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }
}
