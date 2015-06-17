<?php

namespace DvsaCommonApi\Factory;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use DvsaCommonApi\Hydrator\Strategy\ProxyObjectsStrategy;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HydratorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $hydrator = new DoctrineObject($serviceLocator->get(EntityManager::class));
        /*
         * This once caused a problem described below, but seems to be working now.
         *
         * QUOTE:
         * Strategy currently not in use due to errors of the type
         * The class \DoctrineModule\Stdlib\Hydrator\DoctrineObject
         * was not found in the chain configured namespaces DvsaCommonEntities\\Entity,
         */
        $hydrator->addStrategy('*', new ProxyObjectsStrategy());

        return $hydrator;
    }
}
