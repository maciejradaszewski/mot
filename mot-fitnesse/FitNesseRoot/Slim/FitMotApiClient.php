<?php

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\TestShared;

/**
 * ApiClient
 */
class FitMotApiClient
{
    /**
     * @var string
     */
    private $token;

    /**
     * @param CredentialsProvider $credentialsProvider
     *
     * @return FitMotApiClient
     */
    public static function createForCreds(CredentialsProvider $credentialsProvider)
    {
        return new FitMotApiClient($credentialsProvider->username, $credentialsProvider->password);
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return FitMotApiClient
     */
    public static function create($username, $password)
    {
        return new FitMotApiClient($username, $password);
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return FitMotApiClient
     */
    private function __construct($username, $password)
    {
        $this->token = TestShared::getAccessTokenForUser($username, $password);
    }

    /**
     * @param MotFitnesse\Util\AbstractUrlBuilder $urlBuilder
     * @param array|null                          $data null by default
     *
     * @return \stdClass
     */
    public function get($urlBuilder, $data = null)
    {
        $handle = $this->prepareHandle('GET', $urlBuilder, $data);

        return $this->sendRequest($handle);
    }

    /**
     * @param MotFitnesse\Util\AbstractUrlBuilder $urlBuilder
     * @param                                     $data
     *
     * @return \stdClass
     */
    public function post($urlBuilder, $data)
    {
        $handle = $this->prepareHandle('POST', $urlBuilder, $data);

        return $this->sendRequest($handle);
    }

    /**
     * @param MotFitnesse\Util\AbstractUrlBuilder $urlBuilder
     * @param                                     $data
     *
     * @return \stdClass
     */
    public function put($urlBuilder, $data)
    {
        $handle = $this->prepareHandle('PUT', $urlBuilder, $data);

        return $this->sendRequest($handle);
    }

    /**
     * @param MotFitnesse\Util\AbstractUrlBuilder $urlBuilder
     *
     * @return \stdClass
     */
    public function delete($urlBuilder)
    {
        $handle = $this->prepareHandle('DELETE', $urlBuilder);

        return $this->sendRequest($handle);
    }

    /**
     * @param string                              $verb ["GET" | "POST" | "PUT" | "DELETE"]
     * @param MotFitnesse\Util\AbstractUrlBuilder $builder
     * @param null | array                        $data
     *
     * @return resource
     */
    private function prepareHandle($verb, $builder, $data = null)
    {
        $handle = curl_init($builder->toString());
        TestShared::SetupCurlOptions($handle);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $this->headers());
        curl_setopt($handle, CURLOPT_CUSTOMREQUEST, strtoupper($verb));
        if ('GET' !== $verb) {
            curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data));
        }

        return $handle;
    }

    /**
     * @param resource $handle
     *
     * @return \stdClass
     */
    private function sendRequest($handle)
    {
        $result = curl_exec($handle);
        self::checkCurlError($handle);
        self::checkContentType($handle, $result);
        curl_close($handle);
        $jsonResult = self::parseResponse($result);
        self::checkApiErrors($jsonResult);

        return $jsonResult['data'];
    }

    /**
     * @return array
     */
    private function headers()
    {
        $headers = [];
        $headers[] = 'Authorization: Bearer ' . $this->token;
        $headers[] = 'Content-Type: application/json';

        return $headers;
    }

    private static function checkCurlError($handle)
    {
        $curlError = curl_error($handle);
        if ($curlError != "") {
            throw new \Exception("Curl exception: [$curlError]");
        }
    }

    private static function checkContentType($handle, $result)
    {
        $contentType = null;
        $info = curl_getinfo($handle);
        if (array_key_exists('content_type', $info)) {
            $contentType = $info['content_type'];
        }

        if (!strstr($contentType, "application/json")) {
            error_log("Unexpected response: [$result]");
            throw new \Exception("Expected to get JSON, got [$contentType]; response body: [$result]");
        }
    }

    private static function parseResponse($result)
    {
        $jsonResult = json_decode($result, true);

        if (!$jsonResult) {
            error_log("Unable to parse response: [$result]");
            throw new \Exception("Unable to parse response: [$result]");
        }

        return $jsonResult;
    }

    private static function checkApiErrors($jsonResult)
    {
        if (array_key_exists('errors', $jsonResult)) {
            $errorData = null;
            if (array_key_exists('errorData', $jsonResult)) {
                $errorData = $jsonResult['errorData'];
            }
            throw new \ApiErrorException($jsonResult['errors'], $errorData);
        }
    }
}
