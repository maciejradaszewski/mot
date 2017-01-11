<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link https://gitlab.motdev.org.uk/mot/mot
 */

namespace Dvsa\Mot\Frontend\MotTestModuleTest\Factory;

use Dvsa\Mot\Frontend\MotTestModule\Controller\AddManualAdvisoryController;
use Dvsa\Mot\Frontend\MotTestModule\Factory\Controller\AddManualAdvisoryControllerFactory;
use Dvsa\Mot\Frontend\MotTestModule\View\DefectsJourneyContextProvider;
use Dvsa\Mot\Frontend\MotTestModule\View\DefectsJourneyUrlGenerator;
use DvsaCommonTest\TestUtils\ServiceFactoryTestHelper;

class AddManualAdvisoryControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryCreatesInstance()
    {
        ServiceFactoryTestHelper::testCreateServiceForCM(
            AddManualAdvisoryControllerFactory::class,
            AddManualAdvisoryController::class,
            [
                DefectsJourneyUrlGenerator::class => DefectsJourneyUrlGenerator::class,
                DefectsJourneyContextProvider::class => DefectsJourneyContextProvider::class,
            ]
        );
    }
}
