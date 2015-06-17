<?php

namespace DvsaCommon\UrlBuilder;

/**
 *
 */
class CpmsUrlBuilder extends UrlBuilder
{
    const API = '/api';

    const MANDATE = '/mandate[/:token]';

    protected $routesStructure = [
        self::API => [
            self::MANDATE => '',
        ],
    ];

    private $baseUrl;

    /**
     * @param string $baseUrl
     */
    public function __construct($baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/ ');
    }

    public function mandate($token = null)
    {
        $this->appendRoutesAndParams(self::API)
            ->appendRoutesAndParams(self::MANDATE);

        if (!is_null($token)) {
            $this->routeParam('token', $token);
        }

        return $this;
    }

    public function toString()
    {
        return $this->baseUrl . parent::toString();
    }
}
