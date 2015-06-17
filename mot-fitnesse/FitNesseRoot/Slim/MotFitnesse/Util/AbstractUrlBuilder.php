<?php

namespace MotFitnesse\Util;

/**
 * Class AbstractUrlBuilder
 *
 * @package MotFitnesse\Util
 */
class AbstractUrlBuilder
{
    private $mainUrl = TestBase::API_URL;
    private $getParameters = [];
    protected $routesStructure = [];
    protected $routesAndParameters = [];

    /**
     * @return static
     */
    public static function of()
    {
        return new static();
    }

    /**
     * @return $this
     */
    public static function create()
    {
        return new UrlBuilder();
    }

    /**
     * Changes the main URL base (including host); should only be used rarely
     */
    public function forBaseUrl($url)
    {
        $this->mainUrl = $url;

        return $this;
    }

    protected function appendRoutesAndParams($element)
    {
        $this->routesAndParameters[]['route'] = $element;

        return $this;
    }

    /**
     * Adds param to route
     *
     * @param $key   string identifier of parameter
     * @param $value string value of parameter
     *
     * @return $this
     * @throws \RuntimeException when route doesn't require parameter, or parameter have wrong identifier
     */
    public function routeParam($key, $value)
    {
        if (empty($this->routesAndParameters)) {
            throw new \RuntimeException('Providing param when no route specified');
        }

        $noOfRoutes = count($this->routesAndParameters);
        $currentRoute = & $this->routesAndParameters[$noOfRoutes - 1];
        $isParamInRoute = strpos($currentRoute['route'], ":$key");

        if ($isParamInRoute === false) {
            throw new \RuntimeException(
                'Incorrect param ' . $key . ' = ' . $value . ' for route '
                . $currentRoute['route']
            );
        }

        $currentRoute['params'][] = ['key' => $key, 'value' => $value];

        return $this;
    }

    /**
     * @param $key   string identifier of parameter
     * @param $value string value of parameter
     *
     * @return $this
     */
    public function queryParam($key, $value)
    {
        $this->getParameters[$key] = $value;

        return $this;
    }

    /**
     * @param array $params array of parameters to add
     *
     * @return $this
     */
    public function queryParams(array $params)
    {
        $this->getParameters = array_merge($this->getParameters, $params);

        return $this;
    }

    /**
     * Builds url string and checks for errors
     *
     * @return string url
     * @throws \RuntimeException when routes are in wrong order
     */
    public function toString()
    {
        $url = rtrim($this->mainUrl, '/');
        $previousRouteStructure = $this->routesStructure;

        foreach ($this->routesAndParameters as $routeAndParameters) {
            $currentRoute = $routeAndParameters['route'];
            $params = isset($routeAndParameters['params']) ? $routeAndParameters['params'] : [];

            $this->verifyOrderOfRoute($previousRouteStructure, $currentRoute);
            $currentRoute = $this->replaceParametersWithValues($params, $currentRoute);
            $currentRoute = $this->removeUnusedOptionalParameter($currentRoute);

            if (strchr($currentRoute, ':')) {
                throw new \RuntimeException('Missing required parameter in route: ' . $currentRoute);
            }

            $url .= $currentRoute;
        }

        $url = $this->appendQueryParameters($url);

        return $url;
    }

    private function verifyOrderOfRoute(&$previousRouteStructure, $currentRoute)
    {
        $isRouteInCorrectOrder = false;

        if (isset($previousRouteStructure[$currentRoute])) {
            $isRouteInCorrectOrder = true;
            $previousRouteStructure = $previousRouteStructure[$currentRoute];
        }

        if ($isRouteInCorrectOrder === false) {
            throw new \RuntimeException('Routes are in wrong order');
        }
    }

    private function replaceParametersWithValues($params, $route)
    {
        foreach ($params as $param) {
            $route = str_replace(":${param['key']}", "${param['value']}", $route);
        }

        return $route;
    }

    private function removeUnusedOptionalParameter($route)
    {
        $resultRoute = '';
        $beginningIdx = strpos($route, '[', 0);

        if ($beginningIdx === false) {
            return $route;
        }

        $endIdx = strpos($route, ']', $beginningIdx);

        if ($endIdx === false) {
            return $route;
        }

        $resultRoute .= substr($route, 0, $beginningIdx);
        $param = substr($route, $beginningIdx + 1, $endIdx - $beginningIdx - 1);

        if (strpos($param, ':') === false) {
            $resultRoute .= $param;
        }

        return $resultRoute;
    }

    private function appendQueryParameters($url)
    {
        if (count($this->getParameters) > 0) {
            $url .= '?' . http_build_query($this->getParameters);
        }

        return $url;
    }
}
