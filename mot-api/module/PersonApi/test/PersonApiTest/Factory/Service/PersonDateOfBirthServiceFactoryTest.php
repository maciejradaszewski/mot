<?php

namespace PersonApiTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationService;
use DvsaCommon\Validator\DateOfBirthValidator;
use DvsaCommonTest\TestUtils\XMock;
use PersonApi\Factory\Service\PersonDateOfBirthServiceFactory;
use PersonApi\Service\PersonDateOfBirthService;
use Zend\ServiceManager\ServiceManager;

class PersonDateOfBirthServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $entityManager = XMock::of(EntityManager::class);
        $dayOfBirthValidator = XMock::of(DateOfBirthValidator::class);
        $authService = XMock::of(AuthorisationService::class);

        $serviceLocator = new ServiceManager();

        $serviceLocator->setService(EntityManager::class, $entityManager);
        $serviceLocator->setService(DateOfBirthValidator::class, $dayOfBirthValidator);
        $serviceLocator->setService('DvsaAuthorisationService', $authService);

        $factory = new PersonDateOfBirthServiceFactory();
        $result = $factory->createService($serviceLocator);

        $this->assertInstanceOf(PersonDateOfBirthService::class, $result);
    }
}