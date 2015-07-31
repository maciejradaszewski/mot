<?php

namespace DvsaCommon\HttpRestJson;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\HttpRestJson\CachingClient\Cache;
use DvsaCommon\HttpRestJson\CachingClient\CacheContextFactory;
use DvsaCommon\HttpRestJson\Exception\RestServiceUnexpectedContentTypeException;
use Zend\EventManager\EventManagerInterface;

class CachingClient implements Client
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var CacheContextFactory
     */
    private $cacheContextFactory;

    /**
     * @param Client              $client
     * @param Cache               $cache
     * @param CacheContextFactory $cacheContextFactory
     */
    public function __construct(Client $client, Cache $cache, CacheContextFactory $cacheContextFactory)
    {
        $this->client = $client;
        $this->cache = $cache;
        $this->cacheContextFactory = $cacheContextFactory;
    }

    /**
     * @param $token
     */
    public function setAccessToken($token)
    {
        $this->client->setAccessToken($token);
    }

    /**
     * @param $resourcePath
     *
     * @throws RestServiceUnexpectedContentTypeException
     *
     * @return AbstractDataTransferObject
     */
    public function get($resourcePath)
    {
        return $this->fetchFromCacheOrPopulate(
            $resourcePath,
            function () use ($resourcePath) {
                return $this->client->get($resourcePath);
            }
        );
    }

    /**
     * @param string $resourcePath
     *
     * @throws RestServiceUnexpectedContentTypeException
     *
     * @return mixed|string
     */
    public function getPdf($resourcePath)
    {
        return $this->fetchFromCacheOrPopulate(
            $resourcePath,
            function () use ($resourcePath) {
                return $this->client->getPdf($resourcePath);
            }
        );
    }

    /**
     * @param string $resourcePath
     *
     * @throws RestServiceUnexpectedContentTypeException
     *
     * @return mixed|string
     */
    public function getHtml($resourcePath)
    {
        return $this->fetchFromCacheOrPopulate(
            $resourcePath,
            function () use ($resourcePath) {
                return $this->client->getHtml($resourcePath);
            }
        );
    }

    /**
     * @param string $resourcePath
     * @param array  $params
     *
     * @throws RestServiceUnexpectedContentTypeException
     *
     * @return mixed|string
     */
    public function getWithParams($resourcePath, $params)
    {
        return $this->fetchFromCacheOrPopulate(
            $resourcePath,
            function () use ($resourcePath, $params) {
                return $this->client->getWithParams($resourcePath, $params);
            }
        );
    }

    /**
     * @param string $resourcePath
     * @param array  $params
     *
     * @throws RestServiceUnexpectedContentTypeException
     *
     * @return AbstractDataTransferObject
     */
    public function getWithParamsReturnDto($resourcePath, $params)
    {
        return $this->fetchFromCacheOrPopulate(
            $resourcePath,
            function () use ($resourcePath, $params) {
                return $this->client->getWithParamsReturnDto($resourcePath, $params);
            }
        );
    }

    /**
     * @param string $resourcePath
     * @param array  $data
     *
     * @throws RestServiceUnexpectedContentTypeException
     *
     * @return mixed|string
     */
    public function post($resourcePath, $data = [])
    {
        $this->invalidateCache($resourcePath);

        return $this->client->post($resourcePath, $data);
    }

    /**
     * @param string $resourcePath
     * @param array  $data
     *
     * @throws RestServiceUnexpectedContentTypeException
     *
     * @return mixed|string
     */
    public function patch($resourcePath, $data = [])
    {
        $this->invalidateCache($resourcePath);

        return $this->client->patch($resourcePath, $data);
    }

    /**
     * @deprecated All requests are now application/json, use post()
     */
    public function postJson($resourcePath, $data)
    {
        $this->invalidateCache($resourcePath);

        return $this->client->postJson($resourcePath, $data);
    }

    /**
     * @param string $resourcePath
     * @param array  $data
     *
     * @return mixed
     */
    public function put($resourcePath, $data)
    {
        $this->invalidateCache($resourcePath);

        return $this->client->put($resourcePath, $data);
    }

    /**
     * @deprecated All requests are now application/json, use put()
     */
    public function putJson($resourcePath, $data)
    {
        $this->invalidateCache($resourcePath);

        return $this->client->putJson($resourcePath, $data);
    }

    /**
     * @param string $resourcePath
     *
     * @throws RestServiceUnexpectedContentTypeException
     *
     * @return mixed|string
     */
    public function delete($resourcePath)
    {
        $this->invalidateCache($resourcePath);

        return $this->client->delete($resourcePath);
    }

    /**
     * @param EventManagerInterface $eventManager
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->client->setEventManager($eventManager);
    }

    public function getEventManager()
    {
        return $this->client->getEventManager();
    }

    /**
     * @param string   $resourcePath
     * @param callable $callback
     *
     * @return mixed
     */
    private function fetchFromCacheOrPopulate($resourcePath, callable $callback)
    {
        $cacheContext = $this->cacheContextFactory->fromResourcePath($resourcePath);

        $response = $this->cache->fetch($cacheContext);

        if ($response) {
            return $response;
        }

        $response = $callback();

        $this->cache->store($cacheContext, $response);

        return $response;
    }

    /**
     * @param string $resourcePath
     */
    private function invalidateCache($resourcePath)
    {
        $this->cache->invalidate($this->cacheContextFactory->fromResourcePath($resourcePath));
    }
}