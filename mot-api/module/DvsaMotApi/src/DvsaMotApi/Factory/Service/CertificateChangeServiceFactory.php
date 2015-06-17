<?php

namespace DvsaMotApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\CertificateChangeDifferentTesterReason;
use DvsaMotApi\Service\CertificateChangeService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CertificateChangeServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $em = $serviceLocator->get(EntityManager::class);

        return new CertificateChangeService(
            $em->getRepository(CertificateChangeDifferentTesterReason::class),
            $serviceLocator->get('DvsaAuthorisationService')
        );
    }
}
