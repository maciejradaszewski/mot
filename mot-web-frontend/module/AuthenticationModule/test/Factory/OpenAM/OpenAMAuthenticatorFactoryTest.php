<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModuleTest\OpenAM\Factory;

use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use Dvsa\Mot\Frontend\AuthenticationModule\Factory\OpenAM\OpenAMAuthenticatorFactory;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\OpenAMAuthenticator;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthFailureBuilder;
use DvsaCommonTest\TestUtils\ServiceFactoryTestHelper;
use Zend\Log\LoggerInterface;

class OpenAMAuthenticatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryCreatesInstance()
    {
        ServiceFactoryTestHelper::testCreateServiceForSM(
            OpenAMAuthenticatorFactory::class,
            OpenAMAuthenticator::class,
            [
                OpenAMClientInterface::class,
                OpenAMClientOptions::class,
                OpenAMAuthFailureBuilder::class => function () {
                    return new OpenAMAuthFailureBuilder([]);
                },
                'Application\Logger' => LoggerInterface::class,
            ]
        );
    }
}
