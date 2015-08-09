<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModuleTest\Listener\Factory;

use Dvsa\Mot\Frontend\AuthenticationModule\Controller\LogoutController;
use Dvsa\Mot\Frontend\AuthenticationModule\Factory\Controller\LogoutControllerFactory;
use Dvsa\Mot\Frontend\AuthenticationModule\Module;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\WebLogoutService;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use DvsaCommonTest\TestUtils\ServiceFactoryTestHelper;
use DvsaFeature\FeatureToggles;
use Zend\Mvc\Router\Http\TreeRouteStack;

class LogoutControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testWithDasEnabled()
    {
        $router = function() {
            $router = $this
                ->getMockBuilder(TreeRouteStack::class)
                ->disableOriginalConstructor()
                ->getMock();
            $router
                ->expects($this->once())
                ->method('assemble')
                ->willReturn('http://mot-web-frontend.mot.gov.uk');

            return $router;
        };

        ServiceFactoryTestHelper::testCreateServiceForCM(
            LogoutControllerFactory::class,
            LogoutController::class,
            [
                WebLogoutService::class,
                'Feature\FeatureToggles' => [$this, 'createFeatureTogglesWithDasEnabled'],
                'Router' => $router,
                OpenAMClientOptions::class
            ]
        );
    }

    public function testWithDasDisabled()
    {
        ServiceFactoryTestHelper::testCreateServiceForCM(
            LogoutControllerFactory::class,
            LogoutController::class,
            [
                WebLogoutService::class,
                'Feature\FeatureToggles' => [$this, 'createFeatureTogglesWithDasDisabled']
            ]
        );
    }

    /**
     * @return FeatureToggles
     */
    public function createFeatureTogglesWithDasEnabled()
    {
        return $this->createFeatureToggles(true);
    }

    /**
     * @return FeatureToggles
     */
    public function createFeatureTogglesWithDasDisabled()
    {
        return $this->createFeatureToggles(false);
    }

    /**
     * @param bool $enabled
     *
     * @return FeatureToggles
     */
    public function createFeatureToggles($enabled)
    {
        $featureToggles = $this
            ->getMockBuilder(FeatureToggles::class)
            ->disableOriginalConstructor()
            ->getMock();
        $featureToggles
            ->expects($this->once())
            ->method('isEnabled')
            ->will($this->returnValueMap([
                [Module::FEATURE_OPENAM_DAS, $enabled]
            ]));

        return $featureToggles;
    }
}
