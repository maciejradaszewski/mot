<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModuleTest\Factory\Service;

use Dvsa\Mot\Api\RegistrationModule\Factory\Service\OpenAMIdentityCreatorFactory;
use Dvsa\Mot\Api\RegistrationModule\Service\OpenAMIdentityCreator;
use Dvsa\OpenAM\OpenAMClient;
use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use DvsaCommonTest\TestUtils\XMock;
use Zend\ServiceManager\ServiceManager;

/**
 * Class OpenAMIdentityCreatorFactoryTest.
 */
class OpenAMIdentityCreatorServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $factory = new OpenAMIdentityCreatorFactory();

        $serviceManager = new ServiceManager();

        $serviceManager->setService(
            OpenAMClientInterface::class,
            XMock::of(OpenAMClient::class)
        )->setService(
            OpenAMClientOptions::class,
            XMock::of(OpenAMClientOptions::class)
        );

        $this->assertInstanceOf(
            OpenAMIdentityCreator::class,
            $factory->createService($serviceManager)
        );
    }
}
