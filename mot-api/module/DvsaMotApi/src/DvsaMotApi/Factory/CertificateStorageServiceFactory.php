<?php

namespace DvsaMotApi\Factory;

use Doctrine\ORM\EntityManager;
use DvsaMotApi\Service\CertificateStorageService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaMotApi\Service\AmazonS3Service;
use DvsaEntities\Entity\MotTestRecentCertificate;

class CertificateStorageServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
          return new CertificateStorageService(
              $serviceLocator->get(AmazonS3Service::class),
              $serviceLocator->get(EntityManager::class)->getRepository(MotTestRecentCertificate::class),
              $serviceLocator->get('DvsaAuthorisationService')
          );
    }
}
