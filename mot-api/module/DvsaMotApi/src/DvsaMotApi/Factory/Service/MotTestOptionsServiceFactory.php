<?php

namespace DvsaMotApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommonApi\Authorisation\Assertion\ReadMotTestAssertion;
use DvsaEntities\Entity\MotTest;
use DvsaMotApi\Service\MotTestOptionsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MotTestOptionsServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $em */
        $em = $serviceLocator->get(EntityManager::class);

        return new MotTestOptionsService(
            $em->getRepository(MotTest::class),
            $serviceLocator->get(ReadMotTestAssertion::class)
        );
    }
}
