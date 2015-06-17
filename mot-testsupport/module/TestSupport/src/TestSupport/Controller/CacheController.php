<?php

namespace TestSupport\Controller;

use TestSupport\Helper\TestDataResponseHelper;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * Manipulates caches in the application
 */
class CacheController extends AbstractRestfulController
{
    public function apcCacheClearAction()
    {
        apc_clear_cache();

        return TestDataResponseHelper::jsonOk(["message" => "APC cache has been reset"]);
    }

    public function opCacheClearAction()
    {
        opcache_reset();

        return TestDataResponseHelper::jsonOk(["message" => "OPCache has been reset"]);
    }

    public function motApiCacheClearAction()
    {
        $configCachePath = $this->tryGetConfigValue('mot-api-module-config-cache');
        $moduleMapCachePath = $this->tryGetConfigValue('mot-api-module-map-cache');

        $this->removeFile($configCachePath)->removeFile($moduleMapCachePath);

        return TestDataResponseHelper::jsonOk(["message" => "mot-api cache has been reset"]);
    }

    public function motWebFrontendCacheClearAction()
    {
        $configCachePath = $this->tryGetConfigValue('mot-web-frontend-module-config-cache');
        $moduleMapCachePath = $this->tryGetConfigValue('mot-web-frontend-module-map-cache');

        $this->removeFile($configCachePath)->removeFile($moduleMapCachePath);

        return TestDataResponseHelper::jsonOk(["message" => "mot-web-frontend cache has been reset"]);
    }

    public function deleteList()
    {
        opcache_reset();
        apc_clear_cache();
        $this->motApiCacheClearAction();
        $this->motWebFrontendCacheClearAction();

        return TestDataResponseHelper::jsonOk(["message" => "APC, OPCache, mot-api, mot-web-frontend caches have been reset"]);
    }

    private function tryGetConfigValue($key)
    {
        $config = $this->getServiceLocator()->get('config');

        if(!array_key_exists($key, $config)) {
            throw new \Exception("Cannot find config value for key: ".$key);
        }

        return $config[$key];
    }

    private function removeFile($filePath)
    {
        if(file_exists($filePath)) {
            unlink($filePath);
        }

        return $this;
    }
}
