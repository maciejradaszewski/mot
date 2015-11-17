<?php

namespace DvsaCommonApi\Factory;

use DvsaCommon\DtoSerialization\DtoCachedReflector;
use DvsaCommon\DtoSerialization\DtoConvertibleTypesRegistry;
use DvsaCommon\DtoSerialization\DtoReflectiveSerializer;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DtoReflectiveSerializerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $registry = new DtoConvertibleTypesRegistry();
        $serializer = new DtoReflectiveSerializer(new DtoConvertibleTypesRegistry(), new DtoCachedReflector($registry));

        return $serializer;
    }
}
