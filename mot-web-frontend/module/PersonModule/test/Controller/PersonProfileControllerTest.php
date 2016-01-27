<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\PersonModuleTest\Controller;

use Dvsa\Mot\Frontend\PersonModule\View\ContextProvider;
use Dvsa\Mot\Frontend\Test\HttpControllerTestCase;

class PersonProfileControllerTest extends HttpControllerTestCase
{
    public function setUp()
    {
        // Avoid calling parent::setUp as it will cause an Error.
    }

    public function testNotFoundExceptionThrownIfNewProfileFeatureToggleIsDisabled()
    {
        $this->markTestSkipped('Skipped until HttpControllerTestCase becomes functional');

        $url = $this->url(ContextProvider::YOUR_PROFILE_PARENT_ROUTE);
        $this->dispatch($url);
    }
}
