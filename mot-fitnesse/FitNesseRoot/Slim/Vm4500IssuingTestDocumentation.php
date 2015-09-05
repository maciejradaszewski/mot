<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Vm4500IssuingTestDocumentation
{
    private $username;

    private $motTestNumber;

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setMotTestNumber($motTestNumber)
    {
        $this->motTestNumber = $motTestNumber;
    }

    public function canPrint()
    {
        $url = UrlBuilder::of()->printCertificate()
                               ->routeParam('motTestId', $this->motTestNumber);

        $ch = curl_init($url->toString());

        // curl headers
        $headers = [
            'Accept: application/pdf',
            TestShared::getAuthorizationHeaderForUser($this->username, TestShared::PASSWORD),
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_exec($ch);

        // request info
        $info = curl_getinfo($ch);
        $statusCode = (int)$info['http_code'];

        curl_close($ch);  // clean up

        debug(__METHOD__, [$url->toString(), $statusCode, $headers]);

        // checking that we have not failed authorisation for this mot test
        // not if it's a 200!!
        if ($statusCode === 403) {
            return 'NOT ACCESS';
        } elseif ($statusCode === 404) {
            return 'TEST NOT FOUND';
        } elseif ($statusCode === 503) {
            return 'JASPER IS DOWN';
        }

        return 0 === strpos((string) $statusCode, '2') ?: 'ERROR '.$statusCode;
    }
}
