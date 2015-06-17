<?php

namespace DvsaCommon\Factory\Validator;

use Doctrine\Tests\ORM\Mapping\User;
use DvsaCommon\Validator\UsernameValidator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UsernameValidatorFactory.
 */
class UsernameValidatorFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return UsernameValidator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $options = isset($config[UsernameValidator::class]['options']) ?
            $config[UsernameValidator::class]['options'] : [];

        return new UsernameValidator($options);
    }
}
