<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\PersonModuleTest;

use Dvsa\Mot\Frontend\PersonModule\Module;

/**
 * Dvsa\Mot\Frontend\PersonModule tests.
 */
class ModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testGetConfig()
    {
        $module = new Module();

        $config = $module->getConfig();

        $this->assertInternalType('array', $config);
        $this->assertSame($config, unserialize(serialize($config)));
    }

    public function testGetControllerConfig()
    {
        $module = new Module();

        $config = $module->getControllerConfig();

        $this->assertInternalType('array', $config);
    }

    public function testGetServiceConfig()
    {
        $module = new Module();

        $config = $module->getServiceConfig();

        $this->assertInternalType('array', $config);
    }
}
