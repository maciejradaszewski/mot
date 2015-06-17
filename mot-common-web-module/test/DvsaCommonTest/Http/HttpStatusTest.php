<?php

namespace DvsaCommonTest\Http;

use DvsaCommon\Http\HttpStatus;

/**
 * Test class for DvsaCommon\Http\HttpStatus.
 *
 * @covers \DvsaCommon\Http\HttpStatus
 */
class HttpStatusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param integer $constant
     * @param integer $code
     *
     *  @dataProvider constantsProvider
     */
    public function testStatusCodeConstants($constant, $code)
    {
        $this->assertEquals($constant, $code);
    }

    public function testStatusTextsKeyExists()
    {
        $keys = [
            100, 101, 102, 200, 201, 202, 203, 204, 205, 206, 207, 208, 226, 300, 301, 302, 303, 304, 305, 306, 307,
            308, 400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417, 418, 422,
            423, 424, 425, 426, 428, 429, 431, 500, 501, 502, 503, 504, 505, 506, 507, 508, 510, 511,
        ];

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, HttpStatus::$statusTexts);
        }
    }

    /**
     * @param string $code
     * @param string $text
     *
     * @dataProvider statusTextsProvider
     */
    public function testStatusTextsArray($code, $text)
    {
        $this->assertEquals($code, $text);
    }

    /**
     * @return array
     */
    public function constantsProvider()
    {
        return [
            [HttpStatus::HTTP_CONTINUE, 100],
            [HttpStatus::HTTP_SWITCHING_PROTOCOLS, 101],
            [HttpStatus::HTTP_PROCESSING, 102],
            [HttpStatus::HTTP_OK, 200],
            [HttpStatus::HTTP_CREATED, 201],
            [HttpStatus::HTTP_ACCEPTED, 202],
            [HttpStatus::HTTP_NON_AUTHORITATIVE_INFORMATION, 203],
            [HttpStatus::HTTP_NO_CONTENT, 204],
            [HttpStatus::HTTP_RESET_CONTENT, 205],
            [HttpStatus::HTTP_PARTIAL_CONTENT, 206],
            [HttpStatus::HTTP_MULTI_STATUS, 207],
            [HttpStatus::HTTP_ALREADY_REPORTED, 208],
            [HttpStatus::HTTP_IM_USED, 226],
            [HttpStatus::HTTP_MULTIPLE_CHOICES, 300],
            [HttpStatus::HTTP_MOVED_PERMANENTLY, 301],
            [HttpStatus::HTTP_FOUND, 302],
            [HttpStatus::HTTP_SEE_OTHER, 303],
            [HttpStatus::HTTP_NOT_MODIFIED, 304],
            [HttpStatus::HTTP_USE_PROXY, 305],
            [HttpStatus::HTTP_RESERVED, 306],
            [HttpStatus::HTTP_TEMPORARY_REDIRECT, 307],
            [HttpStatus::HTTP_PERMANENTLY_REDIRECT, 308],
            [HttpStatus::HTTP_BAD_REQUEST, 400],
            [HttpStatus::HTTP_UNAUTHORIZED, 401],
            [HttpStatus::HTTP_PAYMENT_REQUIRED, 402],
            [HttpStatus::HTTP_FORBIDDEN, 403],
            [HttpStatus::HTTP_NOT_FOUND, 404],
            [HttpStatus::HTTP_METHOD_NOT_ALLOWED, 405],
            [HttpStatus::HTTP_NOT_ACCEPTABLE, 406],
            [HttpStatus::HTTP_PROXY_AUTHENTICATION_REQUIRED, 407],
            [HttpStatus::HTTP_REQUEST_TIMEOUT, 408],
            [HttpStatus::HTTP_CONFLICT, 409],
            [HttpStatus::HTTP_GONE, 410],
            [HttpStatus::HTTP_LENGTH_REQUIRED, 411],
            [HttpStatus::HTTP_PRECONDITION_FAILED, 412],
            [HttpStatus::HTTP_REQUEST_ENTITY_TOO_LARGE, 413],
            [HttpStatus::HTTP_REQUEST_URI_TOO_LONG, 414],
            [HttpStatus::HTTP_UNSUPPORTED_MEDIA_TYPE, 415],
            [HttpStatus::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE, 416],
            [HttpStatus::HTTP_EXPECTATION_FAILED, 417],
            [HttpStatus::HTTP_I_AM_A_TEAPOT, 418],
            [HttpStatus::HTTP_UNPROCESSABLE_ENTITY, 422],
            [HttpStatus::HTTP_LOCKED, 423],
            [HttpStatus::HTTP_FAILED_DEPENDENCY, 424],
            [HttpStatus::HTTP_RESERVED_FOR_WEBDAV_ADVANCED_COLLECTIONS_EXPIRED_PROPOSAL, 425],
            [HttpStatus::HTTP_UPGRADE_REQUIRED, 426],
            [HttpStatus::HTTP_PRECONDITION_REQUIRED, 428],
            [HttpStatus::HTTP_TOO_MANY_REQUESTS, 429],
            [HttpStatus::HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE, 431],
            [HttpStatus::HTTP_INTERNAL_SERVER_ERROR, 500],
            [HttpStatus::HTTP_NOT_IMPLEMENTED, 501],
            [HttpStatus::HTTP_BAD_GATEWAY, 502],
            [HttpStatus::HTTP_SERVICE_UNAVAILABLE, 503],
            [HttpStatus::HTTP_GATEWAY_TIMEOUT, 504],
            [HttpStatus::HTTP_VERSION_NOT_SUPPORTED, 505],
            [HttpStatus::HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL, 506],
            [HttpStatus::HTTP_INSUFFICIENT_STORAGE, 507],
            [HttpStatus::HTTP_LOOP_DETECTED, 508],
            [HttpStatus::HTTP_NOT_EXTENDED, 510],
            [HttpStatus::HTTP_NETWORK_AUTHENTICATION_REQUIRED, 511],
        ];
    }

    /**
     * @return array
     */
    public function statusTextsProvider()
    {
        return [
            [HttpStatus::$statusTexts[100], 'Continue'],
            [HttpStatus::$statusTexts[101], 'Switching Protocols'],
            [HttpStatus::$statusTexts[102], 'Processing'],
            [HttpStatus::$statusTexts[200], 'OK'],
            [HttpStatus::$statusTexts[201], 'Created'],
            [HttpStatus::$statusTexts[202], 'Accepted'],
            [HttpStatus::$statusTexts[203], 'Non-Authoritative Information'],
            [HttpStatus::$statusTexts[204], 'No Content'],
            [HttpStatus::$statusTexts[205], 'Reset Content'],
            [HttpStatus::$statusTexts[206], 'Partial Content'],
            [HttpStatus::$statusTexts[207], 'Multi-Status'],
            [HttpStatus::$statusTexts[208], 'Already Reported'],
            [HttpStatus::$statusTexts[226], 'IM Used'],
            [HttpStatus::$statusTexts[300], 'Multiple Choices'],
            [HttpStatus::$statusTexts[301], 'Moved Permanently'],
            [HttpStatus::$statusTexts[302], 'Found'],
            [HttpStatus::$statusTexts[303], 'See Other'],
            [HttpStatus::$statusTexts[304], 'Not Modified'],
            [HttpStatus::$statusTexts[305], 'Use Proxy'],
            [HttpStatus::$statusTexts[306], 'Reserved'],
            [HttpStatus::$statusTexts[307], 'Temporary Redirect'],
            [HttpStatus::$statusTexts[308], 'Permanent Redirect'],
            [HttpStatus::$statusTexts[400], 'Bad Request'],
            [HttpStatus::$statusTexts[401], 'Unauthorized'],
            [HttpStatus::$statusTexts[402], 'Payment Required'],
            [HttpStatus::$statusTexts[403], 'Forbidden'],
            [HttpStatus::$statusTexts[404], 'Not Found'],
            [HttpStatus::$statusTexts[405], 'Method Not Allowed'],
            [HttpStatus::$statusTexts[406], 'Not Acceptable'],
            [HttpStatus::$statusTexts[407], 'Proxy Authentication Required'],
            [HttpStatus::$statusTexts[408], 'Request Timeout'],
            [HttpStatus::$statusTexts[409], 'Conflict'],
            [HttpStatus::$statusTexts[410], 'Gone'],
            [HttpStatus::$statusTexts[411], 'Length Required'],
            [HttpStatus::$statusTexts[412], 'Precondition Failed'],
            [HttpStatus::$statusTexts[413], 'Request Entity Too Large'],
            [HttpStatus::$statusTexts[414], 'Request-URI Too Long'],
            [HttpStatus::$statusTexts[415], 'Unsupported Media Type'],
            [HttpStatus::$statusTexts[416], 'Requested Range Not Satisfiable'],
            [HttpStatus::$statusTexts[417], 'Expectation Failed'],
            [HttpStatus::$statusTexts[418], 'I\'m a teapot'],
            [HttpStatus::$statusTexts[422], 'Unprocessable Entity'],
            [HttpStatus::$statusTexts[423], 'Locked'],
            [HttpStatus::$statusTexts[424], 'Failed Dependency'],
            [HttpStatus::$statusTexts[425], 'Reserved for WebDAV advanced collections expired proposal'],
            [HttpStatus::$statusTexts[426], 'Upgrade Required'],
            [HttpStatus::$statusTexts[428], 'Precondition Required'],
            [HttpStatus::$statusTexts[429], 'Too Many Requests'],
            [HttpStatus::$statusTexts[431], 'Request Header Fields Too Large'],
            [HttpStatus::$statusTexts[500], 'Internal Server Error'],
            [HttpStatus::$statusTexts[501], 'Not Implemented'],
            [HttpStatus::$statusTexts[502], 'Bad Gateway'],
            [HttpStatus::$statusTexts[503], 'Service Unavailable'],
            [HttpStatus::$statusTexts[504], 'Gateway Timeout'],
            [HttpStatus::$statusTexts[505], 'HTTP Version Not Supported'],
            [HttpStatus::$statusTexts[506], 'Variant Also Negotiates (Experimental)'],
            [HttpStatus::$statusTexts[507], 'Insufficient Storage'],
            [HttpStatus::$statusTexts[508], 'Loop Detected'],
            [HttpStatus::$statusTexts[510], 'Not Extended'],
            [HttpStatus::$statusTexts[511], 'Network Authentication Required'],
        ];
    }
}
