<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModuleTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use Dvsa\Mot\Api\RegistrationModule\Factory\Service\DuplicatedEmailCheckerFactory;
use Dvsa\Mot\Api\RegistrationModule\Service\DuplicatedEmailChecker;
use DvsaEntities\Entity\Email;
use Doctrine\ORM\EntityRepository;
use DvsaCommonTest\TestUtils\XMock;
use Zend\ServiceManager\ServiceManager;

/**
 * Class DuplicatedEmailCheckerFactoryTest
 * @package Dvsa\Mot\Api\RegistrationModuleTest\Factory\Service
 */
class DuplicatedEmailCheckerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $factory = new DuplicatedEmailCheckerFactory();

        $entityManager = XMock::of(EntityManager::class);
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturn(XMock::of(EntityRepository::class));

        $serviceManager = new ServiceManager();
        $serviceManager->setService(EntityManager::class, $entityManager);

        $this->assertInstanceOf(
            DuplicatedEmailChecker::class,
            $factory->createService($serviceManager)
        );
    }
}
