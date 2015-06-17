<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Datasource\Authentication;
use Dvsa\Mot\Behat\Support\Request;

class CompleteMotTest extends MotApi
{
    const PATH = 'mot-test/{mot_test_number}/status';

    /**
     * @deprecated Use AbstractMotTest::passed which now inherits this behaviour from an abstract class
     */
    public function passed($token, $motNumber)
    {
        return $this->setFinalState($token, $motNumber, 'PASSED');
    }

    /**
     * @deprecated Use AbstractMotTest::failed which now inherits this behaviour from an abstract class
     */
    public function failed($token, $motNumber)
    {
        return $this->setFinalState($token, $motNumber, 'FAILED');
    }

    /**
     * @deprecated Use AbstractMotTest::setFinalState
     */
    public function setFinalState($token, $motNumber, $status, array $params = array())
    {
        $body = json_encode(
            [
                'status' => $status,
                'oneTimePassword' => Authentication::ONE_TIME_PASSWORD,
            ]
        );

        return $this->client->request(
            new Request(
                'POST',
                str_replace('{mot_test_number}', $motNumber, self::PATH),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token],
                $body
            )
        );
    }

    /**
     * @deprecated Use AbstractMotTest::abort
     */
    public function cancelTest($token, $motNumber)
    {
        $body = json_encode(
            [
                'status' => 'ABORTED',
                'reasonForCancelId' => 25,
                'cancelComment' => 'ABORTED TEST',
                'oneTimePassword' => Authentication::ONE_TIME_PASSWORD,
            ]
        );

        return $this->client->request(
            new Request(
                'POST',
                str_replace('{mot_test_number}', $motNumber, self::PATH),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token],
                $body
            )
        );
    }

    /**
     * @deprecated Use AbstractMotTest::abandon
     */
    public function cancelTestWithReason($token, $motNumber, $cancelReasonId)
    {
        $body = json_encode(
            [
                'status' => $cancelReasonId == '7' ? 'ABANDONED' : 'ABORTED',
                'reasonForCancelId' => $cancelReasonId,
                'cancelComment' => 'CANCELLED TEST',
                'oneTimePassword' => Authentication::ONE_TIME_PASSWORD,
            ]
        );

        return $this->client->request(
            new Request(
                'POST',
                str_replace('{mot_test_number}', $motNumber, self::PATH),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token],
                $body
            )
        );
    }

    public function abortTestByVE($token, $motNumber)
    {
        $body = json_encode(
            [
                'reasonForAbort' => 'the test was incorrect',
                'status' => 'ABORTED_VE',
            ]
        );

        return $this->client->request(
            new Request(
                'POST',
                str_replace('{mot_test_number}', $motNumber, self::PATH),
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $token],
                $body
            )
        );
    }
}
