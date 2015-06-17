<?php

namespace DvsaCommon\HttpRestJson\Exception;

use Zend\Http\Headers;

/**
 * Generalization of all Rest Exceptions
 * @package DvsaCommon\HttpRestJson\Exception
 */
class GeneralRestException extends \Exception
{
    protected $resourcePath;
    protected $method;
    protected $postData;

    public function __construct($resourcePath, $method, $postData, $statusCode, $message = null, Headers $requestHeaders = null, Headers $responseHeaders = null)
    {
        $this->resourcePath = $resourcePath;
        $this->method       = $method;
        $this->postData     = $postData;

        $message = $message == null? sprintf("HTTP error [%s], Request Uri [%s], Request Headers [%s], Response Headers: [%s]", (string)$statusCode, $resourcePath, $requestHeaders ? $requestHeaders->toString() : '', $responseHeaders ? $responseHeaders->toString() : '') : $message;
        parent::__construct($message, $statusCode);
    }

    public function getResourcePath()
    {
        return $this->resourcePath;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPostData()
    {
        return $this->postData;
    }
}
