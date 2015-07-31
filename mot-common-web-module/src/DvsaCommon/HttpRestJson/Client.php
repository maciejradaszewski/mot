<?php

namespace DvsaCommon\HttpRestJson;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\HttpRestJson\Exception\RestServiceUnexpectedContentTypeException;
use Zend\EventManager\EventManagerAwareInterface;

interface Client extends EventManagerAwareInterface
{
    /**
     * @param string $token
     */
    public function setAccessToken($token);

    /**
     * @param string $resourcePath
     *
     * @throws RestServiceUnexpectedContentTypeException
     *
     * @return AbstractDataTransferObject
     */
    public function get($resourcePath);

    /**
     * @param string $resourcePath
     *
     * @throws RestServiceUnexpectedContentTypeException
     *
     * @return mixed|string
     */
    public function getPdf($resourcePath);

    /**
     * @param string $resourcePath
     *
     * @throws RestServiceUnexpectedContentTypeException
     *
     * @return mixed|string
     */
    public function getHtml($resourcePath);

    /**
     * @param string $resourcePath
     * @param array  $params
     *
     * @throws RestServiceUnexpectedContentTypeException
     *
     * @return mixed|string
     */
    public function getWithParams($resourcePath, $params);

    /**
     * @param $resourcePath
     * @param $params
     *
     * @throws RestServiceUnexpectedContentTypeException
     *
     * @return AbstractDataTransferObject
     */
    public function getWithParamsReturnDto($resourcePath, $params);

    /**
     * @param string $resourcePath
     * @param array  $data
     *
     * @throws RestServiceUnexpectedContentTypeException
     *
     * @return mixed|string
     */
    public function post($resourcePath, $data = []);

    /**
     * @param string $resourcePath
     * @param array  $data
     *
     * @throws RestServiceUnexpectedContentTypeException
     *
     * @return mixed|string
     */
    public function patch($resourcePath, $data = []);

    /**
     * @deprecated All requests are now application/json, use post()
     */
    public function postJson($resourcePath, $data);

    /**
     * @param string $resourcePath
     * @param array  $data
     *
     * @return mixed
     */
    public function put($resourcePath, $data);

    /**
     * @deprecated All requests are now application/json, use put()
     */
    public function putJson($resourcePath, $data);

    /**
     * @param string $resourcePath
     *
     * @throws RestServiceUnexpectedContentTypeException
     *
     * @return mixed|string
     */
    public function delete($resourcePath);
}
