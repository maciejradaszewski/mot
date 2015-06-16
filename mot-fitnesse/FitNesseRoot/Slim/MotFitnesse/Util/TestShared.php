<?php

namespace MotFitnesse\Util;

use DvsaCommon\Error\ApiErrorCodes;
use DvsaCommon\Utility\ArrayUtils;

class TestShared
{
    const METHOD_POST = 'post';
    const METHOD_GET = 'get';
    const METHOD_PUT = 'put';
    const METHOD_DELETE = 'delete';

    const VIN_QUERY_PARAM = 'vin';
    const REG_QUERY_PARAM = 'reg';
    const VRM_QUERY_PARAM = 'vrm';
    const VIN_TYPE_PARAM = 'vinType';
    const EXCLUDE_DVLA_PARAM = 'excludeDvla';

    const SITE_NUMBER_PARAM = 'siteNumber';
    const TESTER_PARAM = 'tester';
    const VEHICLE_PARAM = 'vehicleId';
    const SEARCH_PARAM = 'search';
    const ROW_COUNT_PARAM = 'rowCount';
    const TYPE_PARAM = 'type';
    const ES_ENABLED = 'esEnabled';
    const SORT_DIRECTION = 'sortDirection';

    const FORMAT_PARAM = 'format';
    const FORMAT_DATA_OBJECT = 'DATA_OBJECT';
    const FORMAT_DATA_TABLES = 'DATA_TABLES';

    const NOT_AVAILABLE = 'N/A';

    const USERNAME_TESTER1 = 'tester1';
    const USERNAME_ENFORCEMENT = 'ft-Enf-tester';
    const USERNAME_SCHEMEUSER = 'schemeuser';
    const USERNAME_SCHEMEMGT = 'schememgt';
    const PASSWORD_ENFORCEMENT = 'Password1';

    const PASSWORD = 'Password1';

    const HTTP_OK_STATUS_CODE = 200;

    const CONTENT_TYPE_JSON = 'application/json';
    const CONTENT_TYPE_PDF = 'application/pdf';
    const CONTENT_TYPE_HTML = 'text/html';

    public static $lastInfo;

    public static function SetupCurlOptions($curlHandle)
    {
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 120);
        curl_setopt($curlHandle, CURLOPT_HEADER, false);
        curl_setopt($curlHandle, CURLOPT_USERAGENT, "Fitnesse;phpcurl;DVSA-MOT");
    }

    public static function SetCurlPost($curlHandle, $data)
    {
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, [self::getJsonContentTypeHeader()]);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, json_encode($data));
    }

    public static function SetCurlPut($curlHandle, $data)
    {
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, [self::getJsonContentTypeHeader()]);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, json_encode($data));
    }

    public static function SetCurlDelete($curlHandle)
    {
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, "DELETE");
    }

    public static function getAccessTokenForUser($username, $password)
    {
        $credentialsKey = $username . ':' . $password;

        $cachedToken = \TokenCache::getToken($credentialsKey);
        if ($cachedToken) {
            return $cachedToken;
        }

        $curlHandle = curl_init((new UrlBuilder())->session()->toString());

        self::SetupCurlOptions($curlHandle);
        $postFields = [
            'username' => $username,
            'password' => $password
        ];
        self::SetCurlPost($curlHandle, $postFields);

        $jsonResult = TestShared::execCurlForJson($curlHandle);

        $accessToken = null;

        if (array_key_exists('errors', $jsonResult)) {
            $error = http_build_query($jsonResult);
            throw new \Exception("Could not login to system with credentials $username:$password, result: $error");
        }
        $accessToken = $jsonResult['data']['accessToken'];

        \TokenCache::addToken($credentialsKey, $accessToken);

        return $accessToken;
    }

    protected static function setCurlOpts(&$curlHandle)
    {
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
    }

    /**
     * @param CredentialsProvider $credentialsProvider
     * @param AbstractUrlBuilder $urlBuilder
     * @param array $postArray POSTed as form post data (NOT json!)
     * @param array $headers custom HTTP headers if needed
     *
     * @return mixed
     */
    public static function execCurlFormPostForJsonFromUrlBuilder(
        $credentialsProvider,
        $urlBuilder,
        $postArray,
        $headers = []
    ) {
        $curlHandle = curl_init($urlBuilder->toString());

        TestShared::SetupCurlOptions($curlHandle);
        TestShared::SetCurlPost($curlHandle, $postArray);
        self::setCurlOpts($curlHandle);

        self::setRequestHeaders(
            (null !== $credentialsProvider) ? $credentialsProvider->username : null,
            (null !== $credentialsProvider) ? $credentialsProvider->password : null,
            $headers,
            $curlHandle
        );

        return TestShared::execCurlForJson($curlHandle);
    }

    /**
     * @param CredentialsProvider $credentialsProvider
     * @param AbstractUrlBuilder $urlBuilder
     * @param array $headers custom HTTP headers if needed
     * @return mixed
     * @throws \Exception
     */
    public static function execCurlFormDeleteForJsonFromUrlBuilder(
        $credentialsProvider,
        $urlBuilder,
        $headers = []
    ) {
        $curlHandle = curl_init($urlBuilder->toString());

        TestShared::SetupCurlOptions($curlHandle);
        TestShared::SetCurlDelete($curlHandle);
        self::setCurlOpts($curlHandle);

        self::setRequestHeaders(
            (null !== $credentialsProvider) ? $credentialsProvider->username : null,
            (null !== $credentialsProvider) ? $credentialsProvider->password : null,
            $headers,
            $curlHandle
        );

        return TestShared::execCurlForJson($curlHandle);
    }

    /**
     * @param $contentType
     * @param $credentialsProvider
     * @param $urlBuilder \MotFitnesse\Util\UrlBuilder
     * @param $postArray  array to be POSTed as form post data (NOT json!)
     * @param $headers    Array of custom HTTP headers if needed
     *
     * @return mixed
     */
    public static function execCurlFormPostForContentTypeFromUrlBuilder(
        $contentType,
        $credentialsProvider,
        $urlBuilder,
        $postArray,
        $headers = []
    ) {
        $curlHandle = curl_init($urlBuilder->toString());

        TestShared::SetupCurlOptions($curlHandle);
        TestShared::SetCurlPost($curlHandle, $postArray);

        self::setCurlOpts($curlHandle);

        self::setRequestHeaders(
            (null !== $credentialsProvider) ? $credentialsProvider->username : null,
            (null !== $credentialsProvider) ? $credentialsProvider->password : null,
            $headers,
            $curlHandle
        );

        return TestShared::execCurlForContentType($contentType, $curlHandle);
    }

    /**
     * @param $credentialsProvider
     * @param $urlBuilder \MotFitnesse\Util\UrlBuilder
     * @param $postArray  array to be POSTed as form post data (NOT json!)
     *
     * @return mixed
     */
    public static function execCurlFormPutForJsonFromUrlBuilder($credentialsProvider, $urlBuilder, $postArray)
    {
        $curlHandle = curl_init($urlBuilder->toString());

        TestShared::SetupCurlOptions($curlHandle);
        TestShared::SetCurlPut($curlHandle, $postArray);

        self::setRequestHeaders(
            (null !== $credentialsProvider) ? $credentialsProvider->username : null,
            (null !== $credentialsProvider) ? $credentialsProvider->password : null,
            [],
            $curlHandle
        );

        return TestShared::execCurlForJson($curlHandle);
    }

    /**
     * @param CredentialsProvider $credentialsProvider
     * @param UrlBuilder $urlBuilder
     * @param array $postArray
     * @param string $method
     *
     * @return \stdClass
     */
    public static function execCurlWithJsonBodyForJsonFromUrlBuilder(
        $credentialsProvider,
        $urlBuilder,
        $postArray,
        $method = self::METHOD_POST
    ) {
        $curlHandle = self::prepareCurlHandleToSendJson(
            $urlBuilder->toString(),
            $method,
            $postArray,
            $credentialsProvider->username,
            $credentialsProvider->password
        );

        return TestShared::execCurlForJson($curlHandle);
    }

    /**
     * @param $credentialsProvider
     * @param $urlBuilder \MotFitnesse\Util\UrlBuilder
     *
     * @return mixed
     */
    public static function execCurlForJsonFromUrlBuilder($credentialsProvider, $urlBuilder)
    {
        $ch = curl_init($urlBuilder->toString());

        self::setRequestHeaders(
            (null !== $credentialsProvider) ? $credentialsProvider->username : null,
            (null !== $credentialsProvider) ? $credentialsProvider->password : null,
            [],
            $ch
        );

        TestShared::SetupCurlOptions($ch);

        return TestShared::execCurlForJson($ch);
    }

    public static function execCurlForJson($curlHandle, $shouldClose = true)
    {
        $curlResult = curl_exec($curlHandle);

        $curlInfo = curl_getinfo($curlHandle);

        $curlError = curl_error($curlHandle);
        if ($curlError != "") {
            throw new \Exception("Curl exception: [$curlError]");
        }

        if ($shouldClose) {
            curl_close($curlHandle);
        }

        $contentType = null;
        if (array_key_exists('content_type', $curlInfo)) {
            $contentType = $curlInfo['content_type'];
        }

        if (false === stripos($contentType, "application/json")) {
            error_log("Unexpected response: [$curlResult]");
            throw new \Exception("Expected to get JSON, got [$contentType]; response body: [$curlResult]");
        }

        $jsonResult = json_decode($curlResult, true);

        if (!$jsonResult) {
            error_log("Unable to parse response: [$curlResult]");
            throw new \Exception("Unable to parse response: [$curlResult]");
        }

        TestShared::$lastInfo = $curlInfo;

        return $jsonResult;
    }

    public static function execCurlForContentType($expectedType, $curlHandle, $shouldClose = true)
    {
        $curlResult = curl_exec($curlHandle);

        $curlInfo = curl_getinfo($curlHandle);

        $curlError = curl_error($curlHandle);
        if ($curlError != "") {
            throw new \Exception("Curl exception: [$curlError]");
        }

        if ($shouldClose) {
            curl_close($curlHandle);
        }

        $contentType = null;
        if (array_key_exists('content_type', $curlInfo)) {
            $contentType = $curlInfo['content_type'];
        }

        if (false === stripos($contentType, $expectedType)) {
            error_log("Unexpected response: [$curlResult]");
            $lastUrl = $curlInfo['url'];
//var_dump($curlInfo); die;
            $requestHeaders = $curlInfo['request_header'];
            throw new \Exception(
                "Expected to get [$expectedType], got [$contentType]; response body: [$curlResult]; Requested url: [$lastUrl]; Request headers: [$requestHeaders]"
            );
        }

        TestShared::$lastInfo = $curlInfo;

        return $curlResult;
    }

    public static function getHeadersFromCurlResponse($response)
    {
        $headers = array();

        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

        foreach (explode("\r\n", $header_text) as $i => $line) {
            if ($i === 0) {
                $headers['http_code'] = $line;
            } else {
                list ($key, $value) = explode(': ', $line);

                $headers[$key] = $value;
            }
        }

        return $headers;
    }

    public static function prepareCurlHandleToSendJsonWithCreds(
        $url,
        $method,
        $postData,
        $credentialsProvider
    ) {
        return self::prepareCurlHandleToSendJson(
            $url,
            $method,
            $postData,
            $credentialsProvider->username,
            $credentialsProvider->password
        );
    }

    public static function prepareCurlHandleToSendJson(
        $url,
        $method = self::METHOD_GET,
        $postData = null,
        $username = null,
        $password = null
    ) {
        $curlHandle = curl_init($url);
        TestShared::SetupCurlOptions($curlHandle);

        if ($method === self::METHOD_POST) {
            TestShared::SetCurlPost($curlHandle, $postData);
        } elseif ($method === self::METHOD_PUT) {
            TestShared::SetCurlPut($curlHandle, $postData);
        } elseif ($method === self::METHOD_DELETE) {
            TestShared::SetCurlDelete($curlHandle);
        }

        $headers = [];
        if ($username) {
            if ($username == self::USERNAME_TESTER1 && !$password) {
                $password = self::PASSWORD_TESTER1;
            }
            $headers[] = TestShared::getAuthorizationHeaderForUser($username, $password);
        }
        $headers[] = TestShared::getJsonContentTypeHeader();
        TestShared::setHeadersForCurl($curlHandle, $headers);

        return $curlHandle;
    }

    public static function executeAndReturnStatusCodeWithAnyErrors($curlHandle, $returnErrors = false)
    {
        $curlResult = curl_exec($curlHandle);
        $info = curl_getinfo($curlHandle);
        curl_close($curlHandle);

        $result = $info['http_code'];
        if ($returnErrors && $info['http_code'] !== 200) {
            $result = $result . " - " . $curlResult;
        }

        return $result;
    }

    /**
     * @param $testObject
     * @param $urlBuilder \MotFitnesse\Util\UrlBuilder
     *
     * @return mixed
     */
    public static function executeAndReturnResponseAsArrayFromUrlBuilder($testObject, $urlBuilder)
    {
        $url = $urlBuilder->toString();
        $curlHandle = curl_init($url);

        self::setRequestHeaders(
            (null !== $testObject) ? $testObject->username : null,
            (null !== $testObject) ? $testObject->password : null,
            [],
            $curlHandle
        );
        TestShared::SetupCurlOptions($curlHandle);

        return TestShared::executeAndReturnResponseAsArray($curlHandle);
    }

    /**
     * @param string $url
     * @param string $username
     * @param string $password
     *
     * @return array
     */
    public static function get($url, $username, $password, $headers = [])
    {
        $curlHandle = curl_init($url);

        self::setRequestHeaders(
            $username,
            $password,
            $headers,
            $curlHandle
        );
        TestShared::SetupCurlOptions($curlHandle);

        return TestShared::executeAndReturnResponseAsArray($curlHandle);
    }

    public static function getPdf($url, $username, $password, $headers = [])
    {
        $data = null;
        $info = [];
        $errors = null;
        $curlHandle = curl_init($url);

        self::setRequestHeaders(
            $username,
            $password,
            $headers,
            $curlHandle
        );
        TestShared::SetupCurlOptions($curlHandle);

        $errorFile = tempnam(sys_get_temp_dir(), 'foo');
        $errors = fopen($errorFile, 'w');

        if ($errors) {

            curl_setopt($curlHandle, CURLOPT_STDERR, $errors);

            try {
                $data = TestShared::execCurlForContentType('application/pdf', $curlHandle, false);
            } catch (\Exception $e) {
                $data = null;
            }

            $info = curl_getinfo($curlHandle);
            fclose($errors);

            $errors = file_get_contents($errorFile);

            curl_close($curlHandle);
            //@unlink($errorFile);
        }

        return [
            'data' => $data,
            'info' => $info,
            'errors' => $errors
        ];
    }

    public static function executeAndReturnResponseAsArray($curlHandle)
    {
        $jsonResult = TestShared::execCurlForJson($curlHandle);
        self::checkResultForErrorsAndThrowException($jsonResult);

        return $jsonResult['data'];
    }

    public static function checkResultForErrorsAndThrowException($jsonResult)
    {
        if (array_key_exists('errors', $jsonResult)) {
            $errorData = null;
            if (array_key_exists('errorData', $jsonResult)) {
                $errorData = $jsonResult['errorData'];
            }
            throw new \ApiErrorException($jsonResult['errors'], $errorData);
        }
    }

    public static function getJsonContentTypeHeader()
    {
        return 'Content-Type: application/json';
    }

    public static function getAuthorizationHeaderForUser($username, $password)
    {
        $accessToken = self::getAccessTokenForUser($username, $password);

        return 'Authorization: Bearer ' . $accessToken;
    }

    public static function setRequestHeaders($username = null, $password = null, $headers = [], $curlHandle)
    {
        if ($username && $password) {
            $headers[] = self::getAuthorizationHeaderForUser($username, $password);
        }
        $headers[] = self::getJsonContentTypeHeader();

        debug(__METHOD__, $headers);

        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);

        return $headers;
    }

    public static function setAuthorizationInHeaderForUser($username, $password, $curlHandle)
    {
        $headers = [];
        $headers[] = self::getAuthorizationHeaderForUser($username, $password);
        $headers[] = self::getJsonContentTypeHeader();

        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);

        return $headers[0];
    }

    public static function setHeadersForCurl($curlHandle, array $headers)
    {
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);
    }

    /**
     * @param \stdClass $result parsed JSON data
     *
     * @return bool
     */
    public static function resultIsSuccess($result)
    {
        return isset($result['data']);
    }

    /**
     * @param array $result parsed JSON data
     *
     * @return string a comma-delimited string of all the displayMessage errors in the JSON
     */
    public static function errorMessages(array $result)
    {
        if (TestShared::resultIsSuccess($result)) {
            return '';
        } else {
            $errorString = '';
            $arrayResult = json_decode(json_encode($result), true);

            if (false === is_array($arrayResult)) {
                return 'result is not array' . strval($arrayResult);
            }

            if (false === isset($arrayResult['errors'])) {
                return 'errors key not found in result' . strval($arrayResult);
            }

            $errors = $arrayResult['errors'];

            if (is_string($errors)) {
                return $errors;
            }

            foreach ($errors as $error) {
                if ($error['code'] === ApiErrorCodes::UNAUTHORISED) {
                    return 'Forbidden';
                } elseif ($errorString === "") {
                    $errorString = self::errorToString($error);
                } else {
                    $errorString = $errorString . "," . self::errorToString($error);
                }
            }

            return $errorString;
        }
    }

    protected static function errorToString($error)
    {
        return ArrayUtils::tryGet($error, 'displayMessage', 'unknown error');
    }
}
