<?php

namespace PersonApiTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DvsaAuthorisation\Service\AuthorisationService;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\PersonContactRepository;
use OrganisationApi\Service\Mapper\PersonContactMapper;
use PersonApi\Factory\Service\PersonContactServiceFactory;
use PersonApi\Service\PersonContactService;
use PersonApi\Service\Validator\PersonalDetailsValidator;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class PersonContactServiceFactoryTest
 *
 * @package PersonApiTest\Factory\Service
 */
class PersonContactServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    public function testCreateServiceReturnsService()
    {
        $entityManager = XMock::of(EntityManager::class);
        $this->mockMethod(
            $entityManager,
            'getRepository',
            $this->at(0),
            XMock::of(
                PersonContactRepository::class
            )
        );
        $this->mockMethod(
            $entityManager,
            'getRepository',
            $this->at(1),
            XMock::of(
                EntityRepository::class
            )
        );

        $mockServiceLocator = XMock::of(ServiceLocatorInterface::class, ['get']);
        $this->mockMethod($mockServiceLocator, 'get', $this->at(0), $entityManager);
        $this->mockMethod($mockServiceLocator, 'get', $this->at(1), XMock::of(PersonContactMapper::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(2), XMock::of(PersonalDetailsValidator::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(3), XMock::of(AuthenticationService::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(4), XMock::of(AuthorisationService::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(5), XMock::of(EntityManager::class));

        $this->assertInstanceOf(
            PersonContactService::class,
            (new PersonContactServiceFactory())->createService($mockServiceLocator)
        );
    }
}
