<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModuleTest\Factory\OpenAM\Response;

use Dvsa\Mot\Frontend\AuthenticationModule\Factory\OpenAM\Response\OpenAMAuthFailureBuilderFactory;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthFailureBuilder;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use DvsaCommonTest\TestUtils\ServiceFactoryTestHelper;
use PHPUnit_Framework_TestCase;

/**
 * OpenAMAuthFailureBuilderFactory test class.
 */
class OpenAMAuthFailureBuilderFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testFactoryCreatesInstance()
    {
        ServiceFactoryTestHelper::testCreateServiceForSM(
            OpenAMAuthFailureBuilderFactory::class,
            OpenAMAuthFailureBuilder::class,
            [
                OpenAMClientOptions::class,
                'Config' => function () {
                    return ['helpdesk' => ['name' => 'DVSA Helpdesk']];
                },
            ]
        );
    }
}
