<?php

namespace DvsaMotApi\Factory;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\CertificateReplacement;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CertificateReplacementRepositoryFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get(EntityManager::class)->getRepository(CertificateReplacement::class);
    }
}
