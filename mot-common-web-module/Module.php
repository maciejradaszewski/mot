<?php

namespace DvsaCommon;

use DvsaApplicationLogger\TokenService\TokenServiceInterface;
use DvsaCommon\HttpRestJson\Client;
use DvsaCommon\Obfuscate\Factory\ParamEncrypterFactory;
use DvsaCommon\Obfuscate\Factory\ParamObfuscatorFactory;
use DvsaCommon\Obfuscate\ParamEncrypter;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommon\Validator\UsernameValidator;
use DvsaCommon\Factory\Validator\UsernameValidatorFactory;
use Zend\Http\Client as HttpClient;
use Zend\Http\Header\GenericHeader;
use Zend\Http\Header\SetCookie;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Loader\ClassMapAutoloader;
use Zend\Loader\StandardAutoloader;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

/**
 * DvsaCommon Module.
 */
class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAutoloaderConfig()
    {
        return [
            ClassMapAutoloader::class => [
                __DIR__ . '/autoload_classmap.php',
            ],
            StandardAutoloader::class => [
                'namespaces' => [__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ]
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Client::class => function ($serviceManager) {
                    $httpClient = new HttpClient();
                    $httpClient->setAdapter(\Zend\Http\Client\Adapter\Curl::class);

                    $config = $serviceManager->get('config');
                    $url = $config['apiUrl'];
                    // Set the UUID for the request, use the logger UUID if set.
                    $requestUuid = uniqid();
                    if (isset($config['DvsaLogger']) && isset($config['DvsaLogger']['RequestUUID'])) {
                        $requestUuid = $config['DvsaLogger']['RequestUUID'];
                    }

                    /** @var TokenServiceInterface $tokenService */
                    $tokenService = $serviceManager->get('tokenService');
                    $logger = isset($config['logApiCalls']) ? $serviceManager->get('Logger') : null;

                    return new Client($httpClient, $url, $tokenService->getToken(), $logger, $requestUuid);
                },
                ParamEncrypter::class                               => ParamEncrypterFactory::class,
                ParamObfuscator::class                              => ParamObfuscatorFactory::class,
                UsernameValidator::class                            => UsernameValidatorFactory::class,
            ],
        ];
    }
}
