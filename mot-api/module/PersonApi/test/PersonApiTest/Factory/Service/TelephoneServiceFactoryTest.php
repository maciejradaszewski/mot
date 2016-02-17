<?php

namespace PersonApiTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use PersonApi\Factory\Service\TelephoneServiceFactory;
use PersonApi\Service\TelephoneService;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use DvsaCommonApi\Service\ContactDetailsService;
use DvsaCommonApi\Filter\XssFilter;

/**
 * Class PersonServiceFactoryTest
 *
 * @package PersonApiTest\Factory\Service
 */
class TelephoneServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    public function testCreateServiceReturnsService()
    {
        $entityManager = XMock::of(EntityManager::class);

        $mockServiceLocator = XMock::of(ServiceLocatorInterface::class, ['get']);
        $this->mockMethod($mockServiceLocator, 'get', $this->at(0), $entityManager);
        $this->mockMethod($mockServiceLocator, 'get', $this->at(1), XMock::of(ContactDetailsService::class));
        $this->assertInstanceOf(
            TelephoneService::class,
            (new TelephoneServiceFactory())->createService($mockServiceLocator)
        );
    }
}
