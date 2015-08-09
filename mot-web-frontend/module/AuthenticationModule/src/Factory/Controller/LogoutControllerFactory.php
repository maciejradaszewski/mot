<?php

namespace Dvsa\Mot\Frontend\AuthenticationModule\Factory\Controller;

use Dvsa\Mot\Frontend\AuthenticationModule\Controller\LogoutController;
use Dvsa\Mot\Frontend\AuthenticationModule\Module;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\WebLogoutService;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for LogoutController instances.
 */
class LogoutControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return LogoutController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Zend\ServiceManager\ServiceManager $serviceLocator */
        $serviceLocator = $serviceLocator->getServiceLocator();

        $logoutWithDas = $serviceLocator->get('Feature\FeatureToggles')->isEnabled(Module::FEATURE_OPENAM_DAS);
        if (true === $logoutWithDas) {
            /** @var \Zend\Mvc\Router\RouteStackInterface $router */
            $router = $serviceLocator->get('Router');
            $gotoUrl = $router->assemble([], ['name' => 'user-home', 'force_canonical' => true]);
            $options = $serviceLocator->get(OpenAMClientOptions::class);
            $dasLogoutUrl = sprintf('%s%s', $options->getLogoutUrl(), urlencode($gotoUrl));
        } else {
            $dasLogoutUrl = null;
        }

        $logoutService = $serviceLocator->get(WebLogoutService::class);

        return new LogoutController($logoutService, $logoutWithDas, $dasLogoutUrl);
    }
}
