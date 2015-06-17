<?php

namespace DvsaCommon\Obfuscate\Factory;

use DvsaCommon\Obfuscate\ParamEncrypter;
use DvsaCommon\Obfuscate\ParamEncoder;
use DvsaCommon\Obfuscate\ParamObfuscator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ParamObfuscatorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ParamObfuscator(
            $serviceLocator->get(ParamEncrypter::class),
            new ParamEncoder(),
            $serviceLocator->get('config')
        );
    }
}
