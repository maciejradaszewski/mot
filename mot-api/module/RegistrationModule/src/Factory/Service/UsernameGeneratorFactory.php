<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModule\Factory\Service;

use Doctrine\ORM\EntityManager;
use Dvsa\Mot\Api\RegistrationModule\Service\UsernameGenerator;
use DvsaEntities\Entity\Person;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

//use DvsaEntities\PersonRepository;

/**
 * Class RegistrationServiceFactory.
 */
class UsernameGeneratorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get(EntityManager::class);

        $personRepository = $entityManager->getRepository(Person::class);

        $service = new UsernameGenerator($personRepository);

        return $service;
    }
}
