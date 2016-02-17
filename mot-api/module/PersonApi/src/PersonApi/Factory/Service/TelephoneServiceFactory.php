<?php

namespace PersonApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Validator\TelephoneNumberValidator;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\ContactDetailsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use PersonApi\Service\TelephoneService;

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

        return new TelephoneService(
            $entityManager,
            $contactDetailsService,
            $validator
        );
    }
}
