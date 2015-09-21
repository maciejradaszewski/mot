<?php

namespace DvsaCommon\Configuration;

/**
 * Wrapper around configuration for more comfortable extraction of values.
 * If a given key does not exists it will throw a proper exception or return a default one.
 *
 * Class MotConfig
 * @package DvsaCommon\Configuration
 */
class MotConfig
{
    private $config;
    private $configExtractor;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->configExtractor = new ConfigExtractor($config);
    }

    /**
     * Gets value from config.
     *
     * @return array
     * @throws ConfigurationKeyMissingException
     */
    public function get()
    {
        $keys = func_get_args();

        if (!$this->configExtractor->keysExist($keys)){
            throw new ConfigurationKeyMissingException($keys);
        }

        return $this->configExtractor->extractValue($keys);
    }

    /**
     * Will get value from config.
     * Will return $defaultValue if value in configuration does not exists.
     *
     * @param $defaultValue
     * @return DefaultConfigExtractor
     */
    public function withDefault($defaultValue)
    {
        return new DefaultConfigExtractor($this->config, $defaultValue);
    }

    /**
     * Checks whether value exists in config.
     *
     * @return bool
     */
    public function valueExists()
    {
        $keys = func_get_args();

        return $this->configExtractor->keysExist($keys);
    }
}
