<?php
namespace DvsaElasticSearch;

use Doctrine\ORM\EntityManager;
use DvsaElasticSearch\Service\ElasticSearchService;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class
 *
 * @package ElasticSearch
 */
class Module implements AutoloaderProviderInterface, ServiceProviderInterface
{
    public function getAutoloaderConfig()
    {
        return [
            \Zend\Loader\StandardAutoloader::class => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }



    /**
     * Note: we only register the configuration service at this point! We manually register
     * the connection and search services once the bootstrap event is fired for this module.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'ElasticSearchService' => function (ServiceLocatorInterface $sm) {
                    return new ElasticSearchService(
                        $sm->get('DvsaAuthorisationService')
                    );
                }
            ],
        ];
    }
}
