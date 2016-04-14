<?php

namespace DvsaCommonApi\Factory;

use DvsaCommon\DtoSerialization\DtoReflectiveDeserializer;
use DvsaCommon\DtoSerialization\DtoReflector;
use DvsaCommon\DtoSerialization\DtoConvertibleTypesRegistry;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DtoReflectiveDeserializerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $registry = new DtoConvertibleTypesRegistry();
        $deserializer = new DtoReflectiveDeserializer(new DtoConvertibleTypesRegistry(), new DtoReflector($registry));

        return $deserializer;
    }
}
