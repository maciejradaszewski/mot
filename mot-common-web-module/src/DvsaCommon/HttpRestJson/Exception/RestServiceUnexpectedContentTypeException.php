<?php

namespace DvsaCommon\HttpRestJson\Exception;

use Zend\Http\Headers;

class RestServiceUnexpectedContentTypeException extends GeneralRestException
{
    public function __construct(
        $resourcePath,
        $method,
        $postData,
        $statusCode,
        $message = null,
        Headers $requestHeaders = null,
        Headers $responseHeaders = null
    ) {
        $this->resourcePath = $resourcePath;
        $this->method       = $method;
        $this->postData     = $postData;

        $message = ($message == null) ? sprintf(
            "The response Content-Type does not match that of the request Accept. Status Code [%s], Request Uri [%s], Request Headers [%s], Response Headers: [%s]",
            (string)$statusCode,
            $resourcePath,
            $requestHeaders ? $requestHeaders->toString() : '',
            $responseHeaders ? $responseHeaders->toString() : ''
        ) : $message;
        parent::__construct($resourcePath, $method, $postData, $statusCode, $message, $requestHeaders);
    }
}
