<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link https://gitlab.motdev.org.uk/mot/mot
 */

namespace Dvsa\Mot\Frontend\MotTestModuleTest\Factory;

use Dvsa\Mot\Frontend\MotTestModule\Controller\AddDefectController;
use Dvsa\Mot\Frontend\MotTestModule\Factory\Controller\AddDefectControllerFactory;
use Dvsa\Mot\Frontend\MotTestModule\View\DefectsJourneyContextProvider;
use Dvsa\Mot\Frontend\MotTestModule\View\DefectsJourneyUrlGenerator;
use DvsaCommonTest\TestUtils\ServiceFactoryTestHelper;

class AddDefectControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryCreatesInstance()
    {
        ServiceFactoryTestHelper::testCreateServiceForCM(
            AddDefectControllerFactory::class,
            AddDefectController::class,
            [
                DefectsJourneyUrlGenerator::class => DefectsJourneyUrlGenerator::class,
                DefectsJourneyContextProvider::class => DefectsJourneyContextProvider::class,
            ]
        );
    }
}
