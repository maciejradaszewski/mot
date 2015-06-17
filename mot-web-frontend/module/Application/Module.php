<?php
namespace Application;

use Application\Data\ApiCurrentMotTest;
use Application\Data\ApiPersonalDetails;
use Application\Data\ApiUserSiteCount;
use Application\Factory\ApplicationWideCacheFactory;
use Application\Factory\ContingencySessionManagerFactory;
use Application\Factory\Data\ApiCurrentMotTestFactory;
use Application\Factory\Data\ApiPersonalDetailsFactory;
use Application\Factory\Data\ApiUserSiteCountFactory;
use Application\Factory\Data\BrakeTestResultsResourceFactory;
use Application\Factory\Data\TesterInProgressTestNumberResourceFactory;
use Application\Factory\LoggedInUserManagerFactory;
use Application\Factory\Service\ReportBuilderServiceFactory;
use Application\Factory\ZendAuthenticationServiceFactory;
use Application\Listener\ClaimAccountListener;
use Application\Listener\Factory\ClaimAccountListenerFactory;
use Application\Listener\ChangeTempPasswordListener;
use Application\Listener\Factory\ChangeTempPasswordListenerFactory;
use Application\Listener\WebListenerEventsPriorities;
use Application\Service\ContingencySessionManager;
use Application\Service\ReportBuilder\Service as ReportBuilderService;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaMotEnforcement\Service\Event;
use DvsaMotEnforcement\Service\ReInspection;
use DvsaMotTest\Data\BrakeTestResultsResource;
use DvsaMotTest\Data\TesterInProgressTestNumberResource;
use DvsaMotTest\Factory\BrakeTestConfigurationContainerFactory;
use DvsaMotTest\Factory\LocationSelectContainerFactory;
use DvsaMotTest\Factory\Service\AuthorisedClassesServiceFactory;
use DvsaMotTest\Mapper\BrakeTestConfigurationClass1And2Mapper;
use DvsaMotTest\Mapper\BrakeTestConfigurationClass3AndAboveMapper;
use DvsaMotTest\Model\BrakeTestConfigurationClass1And2Helper;
use DvsaMotTest\Model\BrakeTestConfigurationClass3AndAboveHelper;
use DvsaMotTest\Service\AuthorisedClassesService;
use DvsaMotTest\Service\VehicleSearchService;
use Zend\Authentication\AuthenticationService;
use Zend\EventManager\EventInterface;
use Zend\Http\Request;
use Zend\Log\Logger;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;
use DvsaMotTest\Model\VehicleSearchResult;
use DvsaMotTest\Factory\Model\VehicleSearchResultFactory;
use DvsaMotTest\Factory\Service\VehicleSearchServiceFactory;

/**
 * Class Module.
 */
class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface,
    BootstrapListenerInterface
{
    const APPLICATION_SESSION = 'applicationSession'; //TODO: duplicated

    public function onBootstrap(EventInterface $e)
    {
        $eventManager = $e->getApplication()->getEventManager();

        $claimAccountListener = $e->getApplication()->getServiceManager()->get(ClaimAccountListener::class);
        $eventManager->attach(
            MvcEvent::EVENT_DISPATCH, $claimAccountListener, WebListenerEventsPriorities::DISPATCH_CLAIM_ACCOUNT
        );

        $changeTempPasswordListener = $e->getApplication()->getServiceManager()->get(ChangeTempPasswordListener::class);
        $eventManager->attach(
            MvcEvent::EVENT_DISPATCH, $changeTempPasswordListener, WebListenerEventsPriorities::DISPATCH_CHANGE_TEMP_PASSWORD
        );

        $eventManager->attach(
            MvcEvent::EVENT_RENDER,
            function ($e) {
                $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
                $viewModel->flashMessenger = new FlashMessenger();
            }
        );

        $eventManager->attach(
            MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'handleError'],
            WebListenerEventsPriorities::DISPATCH_ERROR_HANDLE_ERROR
        );
        $eventManager->attach(
            MvcEvent::EVENT_RENDER_ERROR, [$this, 'handleError'],
            WebListenerEventsPriorities::DISPATCH_ERROR_HANDLE_ERROR
        );

        $config = $e->getApplication()
            ->getServiceManager()
            ->get('Configuration');

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($config['session']);
        $sessionManager = new SessionManager($sessionConfig);

        /** @var Request $request */
        $request = $e->getApplication()->getServiceManager()->get('Request');
        $referrer = $request->getHeader('referer');

        /* VM-8950 */
        $sessionManager->start();
        // FIXME: Move lowest_level_domain to OpenAMClientOptions.
        if ($referrer && stripos($referrer->uri()->getHost(), $config['dvsa_authentication']['openAM']['lowest_level_domain']) === 0) {
            $sessionManager->destroy([
                'send_expire_cookie' => false,
                'clear_storage'      => true,
            ]);
            $sessionManager->start();
            $sessionManager->regenerateId(false);
        }
    }

    public function getConfig()
    {
        return include __DIR__.'/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            \Zend\Loader\ClassMapAutoloader::class => [
                __DIR__.'/autoload_classmap.php',
            ],
            \Zend\Loader\StandardAutoloader::class => [
                'namespaces' => [
                    'Application'           => __DIR__.'/src/Application',
                    'DvsaMotTest'           => __DIR__.'/src/DvsaMotTest',
                    'DvsaMotEnforcement'    => __DIR__.'/src/DvsaMotEnforcement',
                    'DvsaMotEnforcementApi' => __DIR__.'/src/DvsaMotEnforcementApi',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return [
            'factories'  => [
                self::APPLICATION_SESSION                 => \Application\Factory\ApplicationSessionFactory::class,
                'CatalogService'                          => \Application\Factory\Service\CatalogServiceFactory::class,
                'AuthAdapter'                             => \Application\Factory\AuthAdapterFactory::class,
                'ZendAuthenticationService'               => ZendAuthenticationServiceFactory::class,
                'ApplicationWideCache'                    => ApplicationWideCacheFactory::class,
                'MotSession'                              => \Application\Factory\MotSessionFactory::class,
                'FileTemplate'                            => \Application\Factory\FileTemplateFactory::class,
                'Application\Logger'                      => 'Application\Factory\ApplicationLoggerFactory',
                'LoggedInUserManager'                     => LoggedInUserManagerFactory::class,
                ApiPersonalDetails::class                 => ApiPersonalDetailsFactory::class,
                ApiCurrentMotTest::class                  => ApiCurrentMotTestFactory::class,
                ApiUserSiteCount::class                   => ApiUserSiteCountFactory::class,
                'AuthorisedExaminerApplication/Logger'    => \Application\Factory\LoggerFactory::class,
                'Logger'                                  => \Application\Factory\LoggerFactory::class,
                ReportBuilderService::class               => ReportBuilderServiceFactory::class,
                TesterInProgressTestNumberResource::class => TesterInProgressTestNumberResourceFactory::class,
                BrakeTestResultsResource::class           => BrakeTestResultsResourceFactory::class,
                ContingencySessionManager::class          => ContingencySessionManagerFactory::class,
                ClaimAccountListener::class               => ClaimAccountListenerFactory::class,
                ChangeTempPasswordListener::class         => ChangeTempPasswordListenerFactory::class,
                'BrakeTestConfigurationContainerHelper'   => BrakeTestConfigurationContainerFactory::class,
                'LocationSelectContainerHelper'           => LocationSelectContainerFactory::class,
                AuthorisedClassesService::class           => AuthorisedClassesServiceFactory::class,
                VehicleSearchResult::class                => VehicleSearchResultFactory::class,
                VehicleSearchService::class               => VehicleSearchServiceFactory::class
            ],
            'aliases'    => [
                \Zend\Authentication\AuthenticationService::class => 'ZendAuthenticationService'
            ],
            'invokables' => [
                BrakeTestConfigurationClass1And2Helper::class     => BrakeTestConfigurationClass1And2Helper::class,
                BrakeTestConfigurationClass3AndAboveHelper::class => BrakeTestConfigurationClass3AndAboveHelper::class,
                BrakeTestConfigurationClass1And2Mapper::class     => BrakeTestConfigurationClass1And2Mapper::class,
                BrakeTestConfigurationClass3AndAboveMapper::class => BrakeTestConfigurationClass3AndAboveMapper::class,
            ],
        ];
    }

    public function handleError(MvcEvent $e)
    {
        $this->verifyIsError($e);

        $error = $e->getError();

        $exception = $e->getParam('exception');

        $serviceManager = $e->getApplication()->getServiceManager();

        /** @var  $viewManager \Zend\Mvc\View\Console\ViewManager */
        $viewManager = $serviceManager->get('viewManager');

        if ($exception instanceof \DvsaCommon\Auth\NotLoggedInException) {
            // From http://stackoverflow.com/a/14170913/116509
            $url      = $e->getRouter()->assemble([], ['name' => 'login']);
            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);
            $response->sendHeaders();

            $stopCallBack = function ($event) use ($response) {
                $event->stopPropagation();

                return $response;
            };
            $e->getApplication()->getEventManager()->attach(
                MvcEvent::EVENT_ROUTE, $stopCallBack, WebListenerEventsPriorities::ROUTE_STOP_PROPAGATION
            );

            return $response;
        }

        $eid = false;

        /** @var $logger \Zend\Log\Logger */
        $logger = $serviceManager->get('Application/Logger');
        if ($exception) {
            $eid = uniqid();
            $logger->err('error id: ' . $eid);
            $logger->err('error: ' . $error);
            $logger->err('class: ' . get_class($exception));
            $logger->err('file: ' . $exception->getFile());
            $logger->err('line: ' . $exception->getLine());
            $logger->err('message: ' . $exception->getMessage());
            $logger->err('stacktrace: ' . $exception->getTraceAsString());
        }

        $config = $serviceManager->get('config');

        $viewModel = $e->getResult();
        $viewModel->setVariables(['showErrorsInFrontEnd' => $config['showErrorsInFrontEnd'], 'errorId'=>$eid ]);

        if ($exception instanceof \DvsaCommon\HttpRestJson\Exception\GeneralRestException) {
            $e->getResponse()->setStatusCode($exception->getCode());
            if ($exception instanceof \DvsaCommon\HttpRestJson\Exception\NotFoundException) {
                $viewManager->getRouteNotFoundStrategy()->prepareNotFoundViewModel($e);
            }
        } elseif ($exception instanceof UnauthorisedException) {
            $e->getResponse()->setStatusCode(403);
        }

        return $viewModel;
    }

    /**
     * @param MvcEvent $e
     *
     * @throws \LogicException
     */
    private function verifyIsError(MvcEvent $e)
    {
        $error = $e->getError();
        if (!$error) {
            throw new \LogicException("This listener is only meant to be called on errors");
        }
    }
}
