<?php

namespace CensorApi\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaEntities\Repository\CensorPhraseRepository;

class CensorPhraseRepositoryFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $censorPhraseRepository = new CensorPhraseRepository($serviceLocator->get("Doctrine\ORM\EntityManager"));
        return $censorPhraseRepository;
    }
}
