<?php

namespace MotFitnesse\Testing;

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

/**
 *
 */
class ReplacementCertificateHelper
{

    private $username;
    private $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Returns replacement certificate draft id
     *
     * @param $motTestNumber
     *
     * @return int
     */
    public function create($motTestNumber)
    {
        $input = [
            'motTestNumber' => $motTestNumber
        ];
        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            (new UrlBuilder())->replacementCertificateDraft()->toString(),
            TestShared::METHOD_POST,
            $input,
            $this->username,
            $this->password
        );

        $result = TestShared::execCurlForJson($curlHandle);

        return $result;
    }

    public function update($draftId, $data)
    {
        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            (new UrlBuilder())->replacementCertificateDraft()->routeParam('id', $draftId)->toString(),
            TestShared::METHOD_PUT,
            $data,
            $this->username,
            $this->password
        );

        return TestShared::execCurlForJson($curlHandle);
    }

    /**
     * Returns json object (may contain errors)
     *
     * @param      $draftId
     * @param null $oneTimePassword
     *
     * @return mixed
     *
     */
    public function apply($draftId, $oneTimePassword = null)
    {
        $data = $oneTimePassword ? ['oneTimePassword' => $oneTimePassword] : [];

        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            (new UrlBuilder())->replacementCertificateDraft()->routeParam('id', $draftId)
                ->ReplacementCertificateDraftApply()->toString(),
            TestShared::METHOD_POST,
            $data,
            $this->username,
            $this->password
        );

        return TestShared::execCurlForJson($curlHandle);
    }

    public function get($draftId)
    {
        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            (new UrlBuilder())->replacementCertificateDraft()->routeParam('id', $draftId)->toString(),
            TestShared::METHOD_GET,
            null,
            $this->username,
            $this->password
        );

        return TestShared::execCurlForJson($curlHandle);
    }
}
