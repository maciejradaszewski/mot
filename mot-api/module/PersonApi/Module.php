<?php

namespace PersonApi;

/**
 * Class Module.
 */
class Module
{
    public function getConfig()
    {
        return include __DIR__.'/config/module.config.php';
    }
}
