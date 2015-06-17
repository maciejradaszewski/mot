<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\Request;

class ReasonForRejection extends MotApi
{
    const PATH = 'mot-test/{mot_test_id}/reasons-for-rejection';

    public function addFailure($token, $mot_test_number, $rdrId = 8455)
    {
        $body = json_encode([
            'rfrId' => $rdrId,
            'type' => 'FAIL',
            'locationLateral' => 'nearside',
            'locationLongitudinal' => 'front',
            'locationVertical' => 'upper',
            'comment' => 'Description',
            'failureDangerous' => false,
        ]);

        return $this->client->request(new Request(
            'POST',
            str_replace('{mot_test_id}', $mot_test_number, self::PATH),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }

    public function addPrs($token, $mot_test_number, $rdrId = 8455)
    {
        $body = json_encode([
            'rfrId' => $rdrId,
            'type' => 'PRS',
            'locationLateral' => 'nearside',
            'locationLongitudinal' => 'front',
            'locationVertical' => 'upper',
            'comment' => 'Description',
            'failureDangerous' => false,
        ]);

        return $this->client->request(new Request(
            'POST',
            str_replace('{mot_test_id}', $mot_test_number, self::PATH),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            $body
        ));
    }
}
