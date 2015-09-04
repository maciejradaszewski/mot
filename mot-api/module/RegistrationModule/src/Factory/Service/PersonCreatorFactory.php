<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModule\Factory\Service;

use Doctrine\ORM\EntityManager;
use Dvsa\Mot\Api\RegistrationModule\Service\PersonCreator;
use Dvsa\Mot\Api\RegistrationModule\Service\PersonSecurityAnswerRecorder;
use Dvsa\Mot\Api\RegistrationModule\Service\UsernameGenerator;
use DvsaEntities\Entity\AuthenticationMethod;
use DvsaEntities\Entity\Gender;
use DvsaEntities\Entity\Title;
use DvsaEntities\Repository\AuthenticationMethodRepository;
use DvsaEntities\Repository\GenderRepository;
use DvsaEntities\Repository\TitleRepository;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class PersonCreatorFactory.
 */
class PersonCreatorFactory implements FactoryInterface
{
    /**
     * Create service.
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return PersonCreator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var UsernameGenerator $usernameGenerator */
        $usernameGenerator = $serviceLocator->get(UsernameGenerator::class);

        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get(EntityManager::class);

        /** @var TitleRepository $titleRepository */
        $titleRepository = $entityManager->getRepository(Title::class);

        /** @var GenderRepository $genderRepository */
        $genderRepository = $entityManager->getRepository(Gender::class);

        /* @var AuthenticationMethodRepository $genderRepository */
        $authenticationMethodRepository = $entityManager->getRepository(AuthenticationMethod::class);

        /** @var PersonSecurityAnswerRecorder $personSecurityAnswerRecorder */
        $personSecurityAnswerRecorder = $serviceLocator->get(PersonSecurityAnswerRecorder::class);

        $service = new PersonCreator(
            $usernameGenerator,
            $entityManager,
            $authenticationMethodRepository,
            $titleRepository,
            $genderRepository,
            $personSecurityAnswerRecorder
        );

        return $service;
    }
}
