<?php

namespace DvsaCommon\Obfuscate\Factory;

use DvsaCommon\Obfuscate\EncryptionKey;
use DvsaCommon\Obfuscate\ParamEncrypter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ParamEncrypterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');

        if (!isset($config['security']['obfuscate']['key'])) {
            throw new \Exception('Unable to find the config entry for: security ID Obfuscation Key');
        }

        $key = $config['security']['obfuscate']['key'];

        $encryptionKey = new EncryptionKey($key);

        return new ParamEncrypter($encryptionKey);
    }
}
