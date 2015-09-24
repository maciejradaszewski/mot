<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModuleTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Dvsa\Mot\Api\RegistrationModule\Factory\Service\BusinessRoleAssignerFactory;
use Dvsa\Mot\Api\RegistrationModule\Service\BusinessRoleAssigner;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\BusinessRoleStatus;
use DvsaEntities\Entity\PersonSystemRole;
use DvsaEntities\Repository\PersonSystemRoleRepository;
use Zend\ServiceManager\ServiceManager;

/**
 * Class BusinessRoleAssignerFactoryTest.
 */
class BusinessRoleAssignerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $factory = new BusinessRoleAssignerFactory();

        $serviceManager = new ServiceManager();

        $mockEntityManager = XMock::of(EntityManager::class);
        $mockEntityManager->expects($this->any())
            ->method('getRepository')
            ->willReturnCallback(
                function ($entity) {
                    switch ($entity) {
                        case PersonSystemRole::class:
                            return XMock::of(PersonSystemRoleRepository::class);
                            break;
                        case BusinessRoleStatus::class:
                            return XMock::of(EntityRepository::class);
                            break;
                    }

                    return false;
                }
            );

        $serviceManager->setService(
            EntityManager::class,
            $mockEntityManager
        );

        $this->assertInstanceOf(
            BusinessRoleAssigner::class,
            $factory->createService($serviceManager)
        );
    }
}
