<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModule\Factory\Service;

use AccountApi\Crypt\SecurityAnswerHashFunction;
use Doctrine\ORM\EntityManager;
use Dvsa\Mot\Api\RegistrationModule\Service\PersonSecurityAnswerRecorder;
use DvsaEntities\Entity\SecurityQuestion;
use DvsaEntities\Repository\SecurityQuestionRepository;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class PersonSecurityQuestionServiceFactory.
 */
class PersonSecurityAnswerRecorderFactory implements FactoryInterface
{
    /**
     * Create service.
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return PersonSecurityAnswerRecorder
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get(EntityManager::class);

        /** @var SecurityQuestionRepository $securityQuestionsRepository */
        $securityQuestionsRepository = $entityManager->getRepository(SecurityQuestion::class);

        $securityAnswerHashFunction = new SecurityAnswerHashFunction();

        $service = new PersonSecurityAnswerRecorder(
            $securityQuestionsRepository,
            $securityAnswerHashFunction
        );

        return $service;
    }
}
