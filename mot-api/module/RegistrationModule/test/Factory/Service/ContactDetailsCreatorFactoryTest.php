<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModuleTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Dvsa\Mot\Api\RegistrationModule\Factory\Service\ContactDetailsCreatorFactory;
use Dvsa\Mot\Api\RegistrationModule\Service\ContactDetailsCreator;
use DvsaCommonTest\TestUtils\XMock;
use Zend\ServiceManager\ServiceManager;

/**
 * Class ContactDetailsCreatorFactoryTest.
 */
class ContactDetailsCreatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $factory = new ContactDetailsCreatorFactory();

        $serviceManager = new ServiceManager();

        $mockEntityManager = XMock::of(EntityManager::class);
        $mockEntityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn(
                XMock::of(EntityRepository::class)
            );

        $serviceManager->setService(
            EntityManager::class,
            $mockEntityManager
        );

        $this->assertInstanceOf(
            ContactDetailsCreator::class,
            $factory->createService($serviceManager)
        );
    }
}
