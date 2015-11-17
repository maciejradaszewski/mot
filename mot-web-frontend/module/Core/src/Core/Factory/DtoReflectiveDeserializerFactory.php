<?php

namespace Core\Factory;

use DvsaCommon\DtoSerialization\DtoCachedReflector;
use DvsaCommon\DtoSerialization\DtoConvertibleTypesRegistry;
use DvsaCommon\DtoSerialization\DtoReflectiveDeserializer;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DtoReflectiveDeserializerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $registry = new DtoConvertibleTypesRegistry();
        $serializer = new DtoReflectiveDeserializer(new DtoConvertibleTypesRegistry(), new DtoCachedReflector($registry));

        return $serializer;
    }
}