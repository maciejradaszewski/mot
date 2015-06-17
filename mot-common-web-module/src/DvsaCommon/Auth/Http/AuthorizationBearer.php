<?php

namespace DvsaCommon\Auth\Http;

use Zend\Http\Exception\InvalidArgumentException;
use Zend\Http\Header\Exception;
use Zend\Http\Header\GenericHeader;
use Zend\Http\Header\HeaderInterface;

/**
 *
 */
class AuthorizationBearer implements HeaderInterface
{
    const FIELD_NAME = 'Authorization';

    private $token;

    /**
     * @param string $token
     */
    public function __construct($token = '')
    {
        $this->token = $token;
    }

    /**
     * Factory to generate a header object from a string
     *
     * @param string $headerLine
     *
     * @return self
     * @throws Exception\InvalidArgumentException If the header does not match RFC 2616 definition.
     * @see http://tools.ietf.org/html/rfc2616#section-4.2
     */
    public static function fromString($headerLine)
    {
        $header = new static();

        list($name, $value) = GenericHeader::splitHeaderLine($headerLine);

        // check to ensure proper header type for this factory
        if (strtolower($name) !== 'authorization') {
            throw new Exception\InvalidArgumentException(
                'Invalid header line for Authorization string: "' . $name . '"'
            );
        }

        preg_match('/Bearer\\s+(?<token>.*)$/', $value, $matches);
        if (!empty($matches['token'])) {
            $header->token = $matches['token'];
            return $header;
        }
        throw new InvalidArgumentException();
    }

    /**
     * Retrieve header name
     *
     * @return string
     */
    public function getFieldName()
    {
        return self::FIELD_NAME;
    }

    /**
     * Retrieve header value
     *
     * @return string
     */
    public function getFieldValue()
    {
        return 'Bearer ' . $this->token;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Cast to string
     *
     * Returns in form of "NAME: VALUE"
     *
     * @return string
     */
    public function toString()
    {
        return 'Authorization: Bearer ' . $this->token;
    }
}
