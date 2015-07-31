<?php
namespace DvsaCommonTest\HttpRestJson;

use DvsaCommon\HttpRestJson\ZendClient;
use DvsaFeature\FeatureToggles;
use PHPUnit_Framework_Assert;
use PHPUnit_Framework_TestCase;
use Zend\Http\Header\GenericHeader;
use Zend\Http\Request;
use Zend\Http\Response;

/**
 * @package DvsaCommonTest\HttpRestJson
 */
class ZendClientTest extends PHPUnit_Framework_TestCase
{
    const API_URL = "http://test-url.com/";
    const RESOURCE_PATH = "letest";
    const RESOURCE_PATH_WITH_ID = "letest/1";
    const RESOURCE_PATH_NOT_FOUND = "doesnotexit";
    const RESOURCE_PATH_NOT_FOUND_WITH_ID = "doesnotexit/1";

    const CONTENT_TYPE_JSON = 'application/json; charset=utf-8';
    const CONTENT_TYPE_HTML = 'text/html';
    const CONTENT_TYPE_PDF  = 'application/pdf';

    public function testGet()
    {

        $expectedUrl = self::API_URL . self::RESOURCE_PATH;
        $response = new Response();
        $response->setStatusCode(200);
        $response->getHeaders()->addHeaderLine('Content-Type', self::CONTENT_TYPE_JSON);
        $response->setContent('{"data":{"id":"1","active":true}}');

        $client = $this->getHttpClientMock($expectedUrl, 'GET', $response);

        $this->assertSame(
            json_decode($response->getBody(), true),
            $client->get(self::RESOURCE_PATH)
        );
    }

    public function testGetSendsAuthorizationAccessTokenIfSet()
    {
        $accessToken = '52147519c32447.50887395';
        $authorizationHeader = "Bearer $accessToken";

        $expectedUrl = self::API_URL . self::RESOURCE_PATH;
        $response = new Response();
        $response->setContent('{"data":{"message":"Your message was authenticated"}}');

        $client = $this->getHttpClientMock($expectedUrl, 'GET', $response, null, $authorizationHeader);

        $client->get(self::RESOURCE_PATH);
    }

    public function testGetThrowsFor401()
    {
        $expectedUrl = self::API_URL . self::RESOURCE_PATH;
        $response = new Response();
        $response->getHeaders()->addHeaderLine('Content-Type', self::CONTENT_TYPE_JSON);
        $response->setStatusCode(401);
        $response->setContent('{"errors":[{"message":"You do not have permission to see this resource."}]}');

        $client = $this->getHttpClientMock($expectedUrl, 'GET', $response);

        $this->setExpectedException(\DvsaCommon\HttpRestJson\Exception\ForbiddenOrUnauthorisedException::class);
        $client->get(self::RESOURCE_PATH);
    }

    public function testGetThrowsFor403()
    {
        $expectedUrl = self::API_URL . self::RESOURCE_PATH;
        $response = new Response();
        $response->getHeaders()->addHeaderLine('Content-Type', self::CONTENT_TYPE_JSON);
        $response->setStatusCode(403);
        $response->setContent('{"errors":[{"message":"You do not have permission to see this resource."}]}');

        $client = $this->getHttpClientMock($expectedUrl, 'GET', $response);

        $this->setExpectedException(\DvsaCommon\HttpRestJson\Exception\ForbiddenApplicationException::class);
        $client->get(self::RESOURCE_PATH);
    }

    public function testGetThrowsFor404()
    {
        $expectedUrl = self::API_URL . self::RESOURCE_PATH;
        $response = new Response();
        $response->getHeaders()->addHeaderLine('Content-Type', self::CONTENT_TYPE_JSON);
        $response->setStatusCode(404);
        $response->setContent(
            '{"errors":[{"message":"Resource not found.","error":"error-router-no-match","exception":[]}]}'
        );

        $client = $this->getHttpClientMock($expectedUrl, 'GET', $response);

        $this->setExpectedException(\DvsaCommon\HttpRestJson\Exception\NotFoundException::class);
        $client->get(self::RESOURCE_PATH);
    }

    public function testGetThrowFor405()
    {
        $expectedUrl = self::API_URL . self::RESOURCE_PATH;
        $response = new Response();
        $response->setStatusCode(405);
        $response->setContent('{"errors":[{"message":"Action not allowed"}]}');
        $response->getHeaders()->addHeaderLine('Content-Type', self::CONTENT_TYPE_JSON);

        $client = $this->getHttpClientMock($expectedUrl, 'GET', $response);

        $this->setExpectedException(\DvsaCommon\HttpRestJson\Exception\GeneralRestException::class);
        $client->get(self::RESOURCE_PATH);
    }

    public function testGetThrowsFor500()
    {
        $expectedUrl = self::API_URL . self::RESOURCE_PATH;
        $response = new Response();
        $response->setStatusCode(500);
        $response->setContent('{"errors":[{"message":"Everything is broken"}]}');
        $response->getHeaders()->addHeaderLine('Content-Type', self::CONTENT_TYPE_JSON);

        $client = $this->getHttpClientMock($expectedUrl, 'GET', $response);

        $this->setExpectedException(\DvsaCommon\HttpRestJson\Exception\GeneralRestException::class);
        $client->get(self::RESOURCE_PATH);
    }

    public function testPost()
    {
        $expectedUrl = self::API_URL . self::RESOURCE_PATH;
        $response = new Response();
        $response->setContent('{"id":1}');
        $response->getHeaders()->addHeaderLine('Content-Type', self::CONTENT_TYPE_JSON);

        $data = ["active" => true, "val" => "test"];

        $client = $this->getHttpClientMock(
            $expectedUrl,
            'POST',
            $response,
            $data
        );

        $this->assertSame(
            json_decode($response->getBody(), true),
            $client->postJson(self::RESOURCE_PATH, $data)
        );
    }

    public function testPostThrowsFor400()
    {
        $expectedUrl = self::API_URL . self::RESOURCE_PATH;
        $response = new Response();
        $response->setStatusCode(400);
        $response->setContent('{"errors": [{"message": "User dsadas not found","code": 40}]}');
        $response->getHeaders()->addHeaderLine('Content-Type', self::CONTENT_TYPE_JSON);

        $client = $this->getHttpClientMock($expectedUrl, 'POST', $response);

        $this->setExpectedException(\DvsaCommon\HttpRestJson\Exception\RestApplicationException::class);
        $client->postJson(self::RESOURCE_PATH, ['username' => 'test']);
    }

    public function testPut()
    {
        $expectedUrl = self::API_URL . self::RESOURCE_PATH_WITH_ID;
        $response = new Response();
        $response->setContent('{"id":1}');
        $response->getHeaders()->addHeaderLine('Content-Type', self::CONTENT_TYPE_JSON);

        $data = ["id" => 1, "active" => true, "val" => "test"];

        $client = $this->getHttpClientMock(
            $expectedUrl,
            'PUT',
            $response,
            $data
        );

        $this->assertSame(
            json_decode($response->getBody(), true),
            $client->putJson(self::RESOURCE_PATH_WITH_ID, $data)
        );
    }

    public function testPatch()
    {
        $expectedUrl = self::API_URL . self::RESOURCE_PATH_WITH_ID;
        $response = new Response();
        $response->setContent('{"id":1}');
        $response->getHeaders()->addHeaderLine('Content-Type', self::CONTENT_TYPE_JSON);

        $data = ["id" => 1, "active" => true, "val" => "test"];

        $client = $this->getHttpClientMock($expectedUrl, 'PATCH', $response, $data);

        $this->assertSame(
            json_decode($response->getBody(), true),
            $client->patch(self::RESOURCE_PATH_WITH_ID, $data)
        );
    }

    public function testDelete()
    {
        $expectedUrl = self::API_URL . self::RESOURCE_PATH_WITH_ID;
        $response = new Response();
        $response->setContent('{"data":{"message":"deleted"}}');
        $response->getHeaders()->addHeaderLine('Content-Type', self::CONTENT_TYPE_JSON);

        $client = $this->getHttpClientMock($expectedUrl, 'DELETE', $response);

        $this->assertSame(
            json_decode($response->getBody(), true),
            $client->delete(self::RESOURCE_PATH_WITH_ID)
        );
    }

    public function testJsonParsingWithJsonContentType()
    {
        $expectedUrl = self::API_URL . self::RESOURCE_PATH;
        $response = new Response();
        $response->setStatusCode(200);
        $response->setContent('{"data":{"id":"1","active":true}}');
        $response->getHeaders()->addHeaderLine('Content-Type', self::CONTENT_TYPE_JSON);

        $client = $this->getHttpClientMock($expectedUrl, 'GET', $response);

        $this->assertSame(
            json_decode($response->getBody(), true),
            $client->get(self::RESOURCE_PATH)
        );
    }

    public function testNoParsingForNonJsonResponseType()
    {
        $samplePdf = '<html>something</html>';

        $expectedUrl = self::API_URL . self::RESOURCE_PATH;
        $response = new Response();
        $response->getHeaders()->addHeaderLine('Content-Type', self::CONTENT_TYPE_PDF);
        $response->setStatusCode(200);
        $response->setContent($samplePdf);

        $client = $this->getHttpClientMock($expectedUrl, 'GET', $response, null, null, self::CONTENT_TYPE_PDF);

        $this->assertSame(
            $samplePdf,
            $client->getPdf(self::RESOURCE_PATH)
        );
    }


    protected function getHttpClientMock(
        $expectedUrl,
        $expectedMethod,
        $response,
        $expectedData = null,
        $expectedAuthorizationHeader = null,
        $expectedAcceptHeader = self::CONTENT_TYPE_JSON
    ) {
        $httpClientMock = $this->getMockBuilder(\Zend\Http\Client::class)
                                ->disableOriginalConstructor()
                                ->getMock();

        $httpClientMock->expects($this->any())
                        ->method('dispatch')
                        ->with($this->isInstanceOf(\Zend\Http\Request::class))
                        ->will(
                            $this->returnCallback(
                                function(Request $request) use (
                                    $expectedUrl,
                                    $expectedMethod,
                                    $response,
                                    $expectedData,
                                    $expectedAuthorizationHeader,
                                    $expectedAcceptHeader
                                ) {
                                    PHPUnit_Framework_Assert::assertSame($expectedUrl, $request->getUriString());
                                    PHPUnit_Framework_Assert::assertSame($expectedMethod, $request->getMethod());
                                    PHPUnit_Framework_Assert::assertSame(
                                        str_replace(' ', '', $expectedAcceptHeader),
                                        $request->getHeaders('Accept')->getFieldValue(),
                                        'Accept header does not match response content-type'
                                    );

                                    if ($expectedData) {
                                        PHPUnit_Framework_Assert::assertSame(
                                            json_encode($expectedData), $request->getContent()
                                        );
                                    }
                                    return $response;
                                }
                            )
                        );

        $client = new ZendClient($httpClientMock, self::API_URL, null, null, uniqid());

        return $client;
    }
}
