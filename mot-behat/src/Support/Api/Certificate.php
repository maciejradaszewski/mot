<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\HttpClient;
use Dvsa\Mot\Behat\Support\Request;

class Certificate extends MotApi
{
    const PATH = 'certificate-print/{:motTestNumber}';
    const PATH_DUPE_CERT = 'certificate-print/100000010240/dup?siteNr=V1234';
    const PATH_JASPER_DOCUMENT = 'http://mot-testsupport/testsupport/document/{:id}/';

    public function requestCertificate($motTestNumber, $accessToken)
    {
        $path = 'http://mot-api/'.str_replace('{:motTestNumber}', $motTestNumber, self::PATH);

        // @todo push this to the HttpClient, get rid of hard coded baseUrl
        $curlHandle = curl_init($path);
        curl_setopt($curlHandle, CURLOPT_FAILONERROR, 1);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 120);
        curl_setopt($curlHandle, CURLOPT_HEADER, false);
        curl_setopt($curlHandle, CURLOPT_USERAGENT, 'Behat,Curl,DVSA-MOT');
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, json_encode([
            'motTestId' => $motTestNumber,
        ]));

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken,
            'Accept: application/pdf',
        ];

        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);

        $curlResult = curl_exec($curlHandle);

        if (curl_error($curlHandle) != '') {
            throw new \Exception('Curl Exception: ' . $path . ' ' . curl_error($curlHandle));
        }

        curl_close($curlHandle);

        return $curlResult;
    }

    public function getDuplicateCertificate($token)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];

        return $this->get(self::PATH_DUPE_CERT, $headers);
    }

    public function getJasperDocument($id, $token)
    {
        if(empty($id)) {
            throw new \Exception("Id is empty");
        }
        $path = str_replace('{:id}', $id, self::PATH_JASPER_DOCUMENT);

        return $this->client->request(new Request(
            'GET',
            $path,
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token]
        ));
    }

    public function deleteJasperDocument($id, $token)
    {
        if(empty($id)) {
            throw new \Exception("Id is empty");
        }
        $path = str_replace('{:id}', $id, self::PATH_JASPER_DOCUMENT);

        return $this->client->request(new Request(
            'DELETE',
            $path,
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token]
        ));
    }
}
