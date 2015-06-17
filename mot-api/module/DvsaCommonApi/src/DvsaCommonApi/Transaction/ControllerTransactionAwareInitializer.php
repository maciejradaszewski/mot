<?php

namespace DvsaCommonApi\Transaction;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Zend 2 component used to inject implementation of TransactionAwareInterface
 * into components implementing it.
 *
 * Class ControllerTransactionAwareInitializer
 *
 * @package DvsaCommonApi\Transaction
 */
class ControllerTransactionAwareInitializer implements InitializerInterface
{
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($instance instanceof TransactionAwareInterface) {
            // has to go through parent locator to access EntityManager ...
            $em = $serviceLocator->getServiceLocator()->get(EntityManager::class);
            $transactionExecutor = new DoctrineTransactionExecutor($em);
            $instance->setTransactionExecutor($transactionExecutor);
        }
    }
}
