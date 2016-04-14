<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModule\Factory\Service;

use Doctrine\ORM\EntityManager;
use Dvsa\Mot\Api\RegistrationModule\Service\ContactDetailsCreator;
use DvsaEntities\Entity\PersonContactType;
use DvsaEntities\Entity\PhoneContactType;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ContactDetailsCreatorFactory.
 */
class ContactDetailsCreatorFactory implements FactoryInterface
{
    /**
     * Create service.
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ContactDetailsCreator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get(EntityManager::class);
        $personContactTypeRepository = $entityManager->getRepository(PersonContactType::class);
        $phoneContactTypeRepository = $entityManager->getRepository(PhoneContactType::class);

        $service = new ContactDetailsCreator(
            $entityManager,
            $personContactTypeRepository,
            $phoneContactTypeRepository
        );

        return $service;
    }
}
