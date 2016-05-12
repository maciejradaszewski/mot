<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\PersonModuleTest\Controller;

use Dvsa\Mot\Frontend\PersonModule\View\ContextProvider;
use Dvsa\Mot\Frontend\Test\HttpControllerTestCase;
use DvsaCommon\Constants\FeatureToggle;

class PersonProfileControllerTest extends HttpControllerTestCase
{
    public function testNotFoundExceptionIsThrownIfNewProfileFeatureToggleIsDisabled()
    {
        $this->markTestIncomplete('Passes locally but not on other environments.');

        $this->withFeatureToggles([FeatureToggle::NEW_PERSON_PROFILE => false]);

        $url = $this->generateUrlFromRoute(ContextProvider::YOUR_PROFILE_PARENT_ROUTE);
        $this->dispatch($url);
    }
}
