<?php
namespace DvsaCommonTest\HttpRestJson\Exception;

use DvsaCommon\HttpRestJson\Exception\RestServiceUnexpectedContentTypeException;

use PHPUnit_Framework_TestCase;
use Zend\Http\Headers;

class RestServiceUnexpectedContentTypeTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultMessage()
    {
        $requestHeaders = new Headers;
        $requestHeaders->addHeaderLine('Content-Type: application/json');
        $requestHeaders->addHeaderLine('Accept: application/json');

        $responseHeaders = new Headers;
        $responseHeaders->addHeaderLine('Content-Type: application/json');
        $responseHeaders->addHeaderLine('Content-Length: 1337');
        $responseHeaders->addHeaderLine('Content-Type: application/json');

        $resourcePath    = 'http://www.bbc.com';
        $method          = 'testMethod';
        $postData        = array('this' => 'that');
        $statusCode      = 200;
        $testException = new \Exception();
        $restException = new RestServiceUnexpectedContentTypeException($resourcePath, $method, $postData, $statusCode, $message = null, $requestHeaders = null, $responseHeaders = null);
        $this->assertInstanceOf('Exception', $restException);
        $this->assertStringStartsWith('The response Content-Type does not match that of the request Accept', $restException->getMessage());
    }
}