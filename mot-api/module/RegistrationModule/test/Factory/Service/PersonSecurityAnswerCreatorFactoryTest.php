<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModuleTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use Dvsa\Mot\Api\RegistrationModule\Factory\Service\PersonSecurityAnswerRecorderFactory;
use Dvsa\Mot\Api\RegistrationModule\Service\PersonSecurityAnswerRecorder;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\SecurityQuestionRepository;
use Zend\ServiceManager\ServiceManager;

/**
 * Class PersonSecurityAnswerCreatorFactoryTest.
 */
class PersonSecurityAnswerCreatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $factory = new PersonSecurityAnswerRecorderFactory();

        $serviceManager = new ServiceManager();

        $mockEntityManager = XMock::of(EntityManager::class);
        $mockEntityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn(
                XMock::of(SecurityQuestionRepository::class)
            );

        $serviceManager->setService(
            EntityManager::class,
            $mockEntityManager
        );

        $this->assertInstanceOf(
            PersonSecurityAnswerRecorder::class,
            $factory->createService($serviceManager)
        );
    }
}
