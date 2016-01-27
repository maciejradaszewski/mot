<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\PersonModuleTest\Factory\View;

use Dvsa\Mot\Frontend\PersonModule\Factory\View\PersonProfileUrlGeneratorViewHelperFactory;
use Dvsa\Mot\Frontend\PersonModule\View\ContextProvider;
use Dvsa\Mot\Frontend\PersonModule\View\PersonProfileUrlGenerator;
use Dvsa\Mot\Frontend\PersonModule\View\PersonProfileUrlGeneratorViewHelper;
use DvsaCommonTest\TestUtils\ServiceFactoryTestHelper;

class PersonProfileUrlGeneratorViewHelperFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryCreatesInstance()
    {
        ServiceFactoryTestHelper::testCreateServiceWithPluginManager(
            PersonProfileUrlGeneratorViewHelperFactory::class,
            PersonProfileUrlGeneratorViewHelper::class,
            [
                PersonProfileUrlGenerator::class => PersonProfileUrlGenerator::class,
                ContextProvider::class => ContextProvider::class,
            ]
        );
    }
}
