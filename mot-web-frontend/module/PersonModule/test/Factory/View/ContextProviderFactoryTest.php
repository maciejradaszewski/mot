<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\PersonModuleTest\Factory\View;

use Dvsa\Mot\Frontend\PersonModule\Factory\View\ContextProviderFactory;
use Dvsa\Mot\Frontend\PersonModule\View\ContextProvider;
use DvsaCommonTest\TestUtils\ServiceFactoryTestHelper;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Router\Http\TreeRouteStack;

class ContextProviderFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryCreatesInstance()
    {
        ServiceFactoryTestHelper::testCreateServiceForSM(
            ContextProviderFactory::class,
            ContextProvider::class,
            [
                'Router' => TreeRouteStack::class,
                'Request' => Request::class,
            ]
        );
    }
}
