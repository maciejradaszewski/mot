<?php

namespace Dvsa\Mot\Behat\Support\Service;

use Zend\Mvc\Application;

/**
 * Class ServiceManager
 * Returns a service manager that allows us to access TestSupport
 * services from inside Behat context classes
 */
class ServiceManager
{

    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    private $serviceManager;

    /**
     * Returns a service manager loaded with the TestSupport configuration
     * so that we can access test support services from within Behat contexts
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        if (!$this->serviceManager) {
            $path = dirname(dirname(dirname(dirname(__DIR__)))) .
                    '/mot-testsupport/config/application.config.php';

            if(!file_exists($path)) {
                throw new \Exception('TestSupport configuration could not be loaded from path ' . $path);
            }

            $config = require $path;
            $this->serviceManager = Application::init($config)->getServiceManager();
        }

        return $this->serviceManager;
    }
}
