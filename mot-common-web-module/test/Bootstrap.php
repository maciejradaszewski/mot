<?php

namespace DvsaCommonTest;

use RuntimeException;
use Zend\Loader\AutoloaderFactory;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayUtils;

error_reporting(E_ALL | E_STRICT);
date_default_timezone_set("UTC");
ini_set('memory_limit', '512M');

/**
 * Class Bootstrap.
 */
class Bootstrap
{
    /**
     * @var ServiceManager
     */
    private static $serviceManager;

    /**
     * @var array
     */
    private static $config;

    /**
     * @param array $appTestConfig
     */
    public static function init(array $appTestConfig = [])
    {
        $zf2ModulePaths = [];

        $modulePaths = ['module', 'vendor'];
        foreach ($modulePaths as $modulePath) {
            if (($path = static::findParentPath($modulePath))) {
                $zf2ModulePaths[] = $path;
            }
        }

        $zf2ModulePaths = implode(PATH_SEPARATOR, $zf2ModulePaths) . PATH_SEPARATOR;
        $zf2ModulePaths .= getenv('ZF2_MODULES_TEST_PATHS')
            ?: (defined('ZF2_MODULES_TEST_PATHS') ? ZF2_MODULES_TEST_PATHS : '');

        static::initAutoloader($appTestConfig);

        // use ModuleManager to load this module and it's dependencies
        $env = getenv('APPLICATION_ENV') ?: 'development';

        $baseConfig = [
            'module_listener_options' => [
                'module_paths' => explode(PATH_SEPARATOR, $zf2ModulePaths),
                'config_glob_paths' => [
                    "config/autoload/{,*.}{global,local,$env}.php",
                    'local-test.php',
                ],
            ],
        ];

        static::$config = ArrayUtils::merge($baseConfig, $appTestConfig);
        static::setupServiceManager();
    }

    /**
     * @return array
     */
    public static function getApplicationConfig()
    {
        return static::$config;
    }

    public static function setupServiceManager()
    {
        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', self::$config);
        $serviceManager->get('ModuleManager')->loadModules();

        static::$serviceManager = $serviceManager;
    }

    /**
     * Prepare the ESEventManager to listen for callbacks and also register the
     * 'ElasticSearchConnection' service now that the configuration is available.
     *
     * See the ESEventManager class for details on why the initialisation process
     * is done this way.
     *
     * @param $serviceManager
     */
    public static function setupElasticSearch($serviceManager)
    {
        $esConfig = $serviceManager->get('ElasticSearchConfig');

        // Register the connection now we have the configuration...
        $serviceManager->setFactory(
            'ElasticSearchConnection',
            function () use ($esConfig) {
                return ESConnection::getConnection($esConfig);
            }
        );

        // Register the ElasticSearch service handler...
        $serviceManager->setFactory(
            'ElasticSearchService',
            function ($sm) use ($esConfig) {
                return new ElasticSearchService(
                    $sm->get(\Doctrine\ORM\EntityManager::class),
                    $sm->get('Hydrator'),
                    $sm->get('AuthorizationService'),
                    $esConfig,
                    $sm->get('ElasticSearchConnection')
                );
            }
        );

        // Tell the ESEventManager about the configuration and connection
        ESEventManager::init($serviceManager);
    }

    /**
     * @return ServiceManager
     */
    public static function getServiceManager()
    {
        return static::$serviceManager;
    }

    public static function getConfig()
    {
        return static::$config;
    }

    protected static function initAutoloader($testConfig)
    {
        $vendorPath = static::findParentPath('vendor');

        if (is_readable($vendorPath . '/autoload.php')) {
            $loader = include $vendorPath . '/autoload.php';
        } else {
            $zf2Path = getenv('ZF2_PATH')
                ?: (defined('ZF2_PATH') ? ZF2_PATH
                    : (is_dir($vendorPath . '/ZF2/library') ? $vendorPath . '/ZF2/library' : false));

            if (!$zf2Path) {
                throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');
            }

            include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
        }

        if (isset($testConfig['test_namespaces'])) {
            AutoloaderFactory::factory(
                [
                    'Zend\Loader\StandardAutoloader' => [
                        'autoregister_zf' => true,
                        'namespaces'      => $testConfig['test_namespaces'],
                    ],
                ]
            );
        }
    }

    protected static function findParentPath($path)
    {
        $dir = getcwd();
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) {
                return false;
            }
            $previousDir = $dir;
        }

        return $dir . '/' . $path;
    }
}
