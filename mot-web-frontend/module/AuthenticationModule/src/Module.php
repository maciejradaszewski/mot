<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModule;

use Csrf\CsrfValidatingListener;
use Csrf\InvalidCsrfException;
use Csrf\Module as CsrfModule;
use Dvsa\Mot\Frontend\AuthenticationModule\Listener\WebAuthenticationListener;
use DvsaCommon\Http\HttpStatus;
use Zend\EventManager\EventInterface;
use Zend\Http\Request as HttpRequest;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;

/**
 * AuthenticationModule handles authentication-related requests.
 */
class Module implements
    BootstrapListenerInterface,
    ConfigProviderInterface,
    ControllerProviderInterface,
    DependencyIndicatorInterface,
    ServiceProviderInterface
{
    const FEATURE_OPENAM_DAS = 'openam.das.enabled';

    /**
     * {@inheritDoc}
     */
    public function init(ModuleManager $moduleManager)
    {
        $events = $moduleManager->getEventManager();

        /*
         * Registering a listener at default priority (1) which will trigger after the ConfigListener merges config.
         *
         * NOTE: If a cached config is used by the ModuleManager, the EVENT_MERGE_CONFIG event will not be triggered.
         * However, typically that means that what is cached will be what was originally manipulated by your listener.
         */
        $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, [$this, 'onMergeConfig']);
    }

    /**
     * {@inheritDoc}
     */
    public function onMergeConfig(ModuleEvent $e)
    {
        $configListener = $e->getConfigListener();
        $config = $configListener->getMergedConfig(false);

        /*
         * Check if the DAS is enabled as a feature toggle.
         *
         * This is a risky move as if the config root key provided by the FeatureToggles module changes we are in
         * trouble. Ideally we would load the module and use the "Dvsa\FeatureToggles::isEnabled()" method to check
         * if the feature is enabled but loading the module here is not an option. So be it.
         */
        if (!isset($config['feature_toggle'][self::FEATURE_OPENAM_DAS])
            || true !== $config['feature_toggle'][self::FEATURE_OPENAM_DAS]) {
            return;
        }

         // Remove the /login route from the configuration.
        if (isset($config['router']['routes']['login'])) {
            unset($config['router']['routes']['login']);
        }

        // Pass the changed configuration back to the listener:
        $configListener->setMergedConfig($config);
    }

    /**
     * {@inheritDoc}
     */
    public function onBootstrap(EventInterface $e)
    {
        if (!($e instanceof MvcEvent) || !($e->getRequest() instanceof HttpRequest)) {
            return;
        }

        $app = $e->getApplication();
        $eventManager = $app->getEventManager();

        $webAuthenticationListener = $app->getServiceManager()->get(WebAuthenticationListener::class);
        $eventManager->attach(MvcEvent::EVENT_ROUTE, $webAuthenticationListener, -1);
        $eventManager->attach(
            MvcEvent::EVENT_DISPATCH,
            [$this, 'handleCsrfExceptions'],
            CsrfModule::CSRF_VALIDATING_LISTENER_PRIORITY + 1
        );
    }

    /**
     * This listener runs with higher priority than CsrfValidatingListener::validate() to be able to catch
     * InvalidCsrfException(s) earlier and redirect to the login page since the EVENT_DISPATCH_ERROR listener is not
     * being executed.
     *
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function handleCsrfExceptions(MvcEvent $e)
    {
        try {
            (new CsrfValidatingListener())->validate($e);
        } catch (InvalidCsrfException $exception) {
            $redirectUrl = $e->getRequest()->getServer('HTTP_REFERER');
            if (!$redirectUrl) {
                $redirectUrl = $e->getRouter()->assemble([], ['name' => 'user-home', 'force_canonical' => true]);
            }
            /*
             * When an MvcEvent Listener returns a Response object it automatically short-circuits the Application
             * running.
             */
            /** @var \Zend\Http\PhpEnvironment\Response $response */
            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $redirectUrl);
            $response->setStatusCode(HttpStatus::HTTP_FOUND);
            $response->sendHeaders();

            // To avoid additional processing we attach a listener for the Route Event with a higher priority.
            $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_ROUTE, function ($event) use ($response) {
                $event->stopPropagation();

                return $response;
            }, 10000);

            return $response;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return array_merge(
            include __DIR__ . '/../config/routes.config.php',
            include __DIR__ . '/../config/module.config.php'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getControllerConfig()
    {
        return include __DIR__ . '/../config/controllers.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getModuleDependencies()
    {
        return ['Application', 'Csrf', 'DvsaFeature'];
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceConfig()
    {
        return include __DIR__ . '/../config/services.config.php';
    }
}
