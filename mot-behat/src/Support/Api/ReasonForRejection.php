<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\Request;

class ReasonForRejection extends MotApi
{
    const REASONS_PATH = 'mot-test/{mot_test_id}/reasons-for-rejection';
    const REASON_PATH = 'mot-test/{mot_test_id}/reason-for-rejection';
    const TEST_ITEM_SELECTOR_PATH = 'mot-test/{mot_test_id}/test-item-selector/{tisId}';

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
            str_replace('{mot_test_id}', $mot_test_number, self::REASONS_PATH),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token],
            $body
        ));
    }
    public function editRFR($token, $mot_test_number, $rdrId = 8455)
    {
        $body = json_encode([
            'id' => $rdrId,
            'locationLateral' => 'nearside',
            'locationLongitudinal' => 'front',
            'locationVertical' => 'upper',
            'comment' => 'Description',
            'failureDangerous' => false,
        ]);

        return $this->client->request(new Request(
            'POST',
            str_replace('{mot_test_id}', $mot_test_number, self::REASONS_PATH),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token],
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
            str_replace('{mot_test_id}', $mot_test_number, self::REASONS_PATH),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token],
            $body
        ));
    }


    /**
     * @param $token
     * @param $motTestNumber
     * @param $term
     * @return \Dvsa\Mot\Behat\Support\Response
     */
    public function search($token, $motTestNumber, $term, $start = null, $end = null)
    {
        $path = str_replace(['{mot_test_id}', '{term}'], [$motTestNumber, $term], self::REASON_PATH);
        $path .= "?search=" . $term;
        if (!is_null($start)) {
            $path .= "&start=" . $start;
        }
        if (!is_null($end)) {
            $path .= "&end=" . $end;
        }

        return $this->sendRequest(
            $token,
            MotApi::METHOD_GET,
            $path
        );
    }

    /**
     * @param $token
     * @param $motTestNumber
     * @param $rootItemId
     * @return \Dvsa\Mot\Behat\Support\Response
     */
    public function listTestItemSelectors($token, $motTestNumber, $rootItemId = 0)
    {
        $path = str_replace(['{mot_test_id}', '{tisId}'], [$motTestNumber, $rootItemId], self::TEST_ITEM_SELECTOR_PATH);

        return $this->sendRequest(
            $token,
            MotApi::METHOD_GET,
            $path
        );
    }
}
