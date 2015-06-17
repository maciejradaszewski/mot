<?php

namespace DvsaCommonApi\Model;

class ApiEndPoint
{
    private $routeName;
    private $method;

    public function __construct($routeName, $method)
    {
        $this->routeName = $routeName;
        $this->method = $method;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function equals(ApiEndPoint $otherEndPoint)
    {
        return $this->routeName === $otherEndPoint->getRouteName()
        && $this->method === $otherEndPoint->getMethod();
    }
}
