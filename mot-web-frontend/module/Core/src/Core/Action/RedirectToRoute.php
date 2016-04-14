<?php

namespace Core\Action;

class RedirectToRoute extends AbstractRedirectActionResult
{
    private $routeName;

    private $routeParams = [];

    private $queryParams = [];

    public function __construct($routeName, $routeParams = [], $queryParams = [])
    {
        $this->routeName = $routeName;
        $this->routeParams = $routeParams;
        $this->queryParams = $queryParams;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }

    public function getRouteParams()
    {
        return $this->routeParams;
    }

    public function getQueryParams()
    {
        return $this->queryParams;
    }
}
