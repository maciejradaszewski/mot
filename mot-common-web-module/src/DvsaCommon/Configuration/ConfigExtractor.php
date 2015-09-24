<?php

namespace DvsaCommon\Configuration;

/**
 * Should not be used on it's own, please use MotConfig.
 * Extracts data from config array.
 *
 * Class ConfigExtractor
 * @package DvsaCommon\Configuration
 */
class ConfigExtractor
{
    private $config;
    public function __construct(array $config){
        $this->config = $config;
    }

    public function keysExist(array $keys)
    {
        $value = $this->config;

        foreach ($keys as $key) {
            if (!array_key_exists($key, $value)) {
                return false;
            }

            $value = $value[$key];
        }

        return true;
    }

    public function extractValue(array $keys) {
        $value = $this->config;

        foreach ($keys as $key) {
            $value = $value[$key];
        }

        return $value;
    }
}
