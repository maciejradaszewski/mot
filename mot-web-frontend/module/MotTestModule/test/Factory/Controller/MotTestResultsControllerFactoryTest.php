<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link https://gitlab.motdev.org.uk/mot/mot
 */

namespace Dvsa\Mot\Frontend\MotTestModuleTest\Factory;

use Dvsa\Mot\Frontend\MotTestModule\Controller\MotTestResultsController;
use Dvsa\Mot\Frontend\MotTestModule\Factory\Controller\MotTestResultsControllerFactory;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommonTest\TestUtils\ServiceFactoryTestHelper;
use PHPUnit_Framework_TestCase;

class MotTestResultsControllerFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testFactoryCreatesInstance()
    {
        ServiceFactoryTestHelper::testCreateServiceForCM(
            MotTestResultsControllerFactory::class,
            MotTestResultsController::class,
            [
                'authorisationHelper' => MotAuthorisationServiceInterface::class,
            ]
        );
    }
}
