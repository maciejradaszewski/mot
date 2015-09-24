<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModuleTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use Dvsa\Mot\Api\RegistrationModule\Factory\Service\PersonCreatorFactory;
use Dvsa\Mot\Api\RegistrationModule\Service\PersonCreator;
use Dvsa\Mot\Api\RegistrationModule\Service\PersonSecurityAnswerRecorder;
use Dvsa\Mot\Api\RegistrationModule\Service\UsernameGenerator;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\AuthenticationMethod;
use DvsaEntities\Entity\Gender;
use DvsaEntities\Entity\Title;
use DvsaEntities\Repository\AuthenticationMethodRepository;
use DvsaEntities\Repository\GenderRepository;
use DvsaEntities\Repository\TitleRepository;
use Zend\ServiceManager\ServiceManager;

/**
 * Class PersonCreatorFactoryTest.
 */
class PersonCreatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $factory = new PersonCreatorFactory();

        $serviceManager = new ServiceManager();

        $mockEntityManager = XMock::of(EntityManager::class);
        $mockEntityManager->expects($this->any())
            ->method('getRepository')
            ->willReturnCallback(
                function ($entity) {
                    switch ($entity) {
                        case AuthenticationMethod::class:
                            return XMock::of(AuthenticationMethodRepository::class);
                            break;
                        case Title::class:
                            return XMock::of(TitleRepository::class);
                            break;
                        case Gender::class:
                            return XMock::of(GenderRepository::class);
                            break;
                    }

                    return false;
                }
            );

        $serviceManager->setService(
            UsernameGenerator::class,
            XMock::of(UsernameGenerator::class)
        )->setService(
            EntityManager::class,
            $mockEntityManager
        )->setService(
            PersonSecurityAnswerRecorder::class,
            XMock::of(PersonSecurityAnswerRecorder::class)
        );

        $this->assertInstanceOf(
            PersonCreator::class,
            $factory->createService($serviceManager)
        );
    }
}
