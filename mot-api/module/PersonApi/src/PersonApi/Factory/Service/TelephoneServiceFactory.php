<?php

namespace PersonApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationService;
use DvsaCommon\Validator\TelephoneNumberValidator;
use DvsaCommonApi\Service\ContactDetailsService;
use PersonApi\Helper\PersonDetailsChangeNotificationHelper;
use PersonApi\Service\TelephoneService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TelephoneServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get(EntityManager::class);

        /** @var ContactDetailsService $contactDetailsService */
        $contactDetailsService = $serviceLocator->get(ContactDetailsService::class);

        /** @var TelephoneNumberValidator $validator */
        $validator = new TelephoneNumberValidator();

        /** @var AuthorisationService $authService */
        $authService = $serviceLocator->get('DvsaAuthorisationService');

        /** @var PersonDetailsChangeNotificationHelper $notificationHelper */
        $notificationHelper = $serviceLocator->get(PersonDetailsChangeNotificationHelper::class);

        return new TelephoneService(
            $entityManager,
            $contactDetailsService,
            $validator,
            $authService,
            $notificationHelper
        );
    }
}
