<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Core\Service;

use DvsaClient\MapperFactory;
use Zend\Session\Container;

/**
 * Class SessionService.
 */
class SessionService
{
    const UNIQUE_KEY = 'DefaultSessionKey';

    /**
     * @var Container
     */
    protected $sessionContainer;

    /**
     * @var MapperFactory
     */
    protected $mapper;

    /**
     * @param Container     $sessionContainer
     * @param MapperFactory $mapper
     */
    public function __construct(Container $sessionContainer, MapperFactory $mapper)
    {
        $this->sessionContainer = $sessionContainer;
        $this->mapper = $mapper;
    }

    /**
     * Clear and Kill the Session.
     */
    public function destroy()
    {
        $this->sessionContainer->getManager()->destroy();
    }

    /**
     * Clear all the data from sessionStorage.
     */
    public function clear()
    {
        /** @var \Zend\Session\Storage\StorageInterface $storage */
        $storage = $this->sessionContainer->getManager()->getStorage();

        $storage->clear(static::UNIQUE_KEY);
    }

    /**
     * @param $key
     *
     * @return array|mixed
     */
    public function load($key)
    {
        if ($this->sessionContainer->offsetExists($key)) {
            return $this->sessionContainer->offsetGet($key);
        }

        return [];
    }

    /**
     * @param $key
     * @param $value
     */
    public function save($key, $value)
    {
        $this->sessionContainer->offsetSet($key, $value);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->sessionContainer->getArrayCopy();
    }
}
