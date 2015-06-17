<?php

namespace Dvsa\Mot\Behat\Support;

class Response
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var null|array
     */
    private $body;

    /**
     * @var array
     */
    private $headers;

    /**
     * @param Request    $request
     * @param int        $statusCode
     * @param array      $headers
     * @param array|null $body
     */
    public function __construct(Request $request, $statusCode = 200, array $headers = [], $body = null)
    {
        $this->request = $request;
        $this->statusCode = $statusCode;
        $this->body = null !== $body ? new ResponseBody($body) : $body;
        $this->headers = $headers;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return ResponseBody|null
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $body = null === $this->body ? '' : $this->body->__toString();
        $headers = $this->getHeadersAsString();

        return <<<RESPONSE
[$this->statusCode]
$headers

$body
RESPONSE;
    }

    /**
     * @return string
     */
    private function getHeadersAsString()
    {
        return implode("\n", array_map(
            function ($header, $value) {
                return $header.': '.(is_array($value) ? implode(',', $value) : $value);
            },
            array_keys($this->headers),
            $this->headers
        ));
    }
}
