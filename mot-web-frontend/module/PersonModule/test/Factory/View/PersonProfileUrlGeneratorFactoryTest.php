<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\PersonModuleTest\Factory\View;

use Dvsa\Mot\Frontend\PersonModule\Factory\View\PersonProfileUrlGeneratorFactory;
use Dvsa\Mot\Frontend\PersonModule\View\ContextProvider;
use Dvsa\Mot\Frontend\PersonModule\View\PersonProfileUrlGenerator;
use DvsaCommonTest\TestUtils\ServiceFactoryTestHelper;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Router\RouteStackInterface;

class PersonProfileUrlGeneratorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryCreatesInstance()
    {
        ServiceFactoryTestHelper::testCreateServiceForSM(
            PersonProfileUrlGeneratorFactory::class,
            PersonProfileUrlGenerator::class,
            [
                'Router' => RouteStackInterface::class,
                'Request' => Request::class,
                ContextProvider::class => ContextProvider::class,
            ]
        );
    }
}
