<?php

namespace TestSupport\Service;

use TestSupport\Helper\TestDataResponseHelper;

class FeaturesService {

    private $config;

    public function __construct($configFile)
    {
        if (!is_readable($configFile)) {
            throw new \InvalidArgumentException(sprintf('Could not read the feature toggle config: "%s"', $configFile));
        }

        $this->config = require_once $configFile;
    }

    public function get($feature)
    {
        $toggle = isset($this->config['feature_toggle'][$feature]) ? (bool) $this->config['feature_toggle'][$feature] : false;

        return TestDataResponseHelper::jsonOk(
            [
                "toggle" => $toggle,
            ]
        );
    }
}