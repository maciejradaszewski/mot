<?php

namespace DvsaCommonApiTest\Stub;

use DvsaEntities\Repository\ConfigurationRepositoryInterface;

/**
 * Class ConfigurationRepositoryStub
 *
 * @package DvsaCommonApiTest\Stub
 */
class ConfigurationRepositoryStub implements ConfigurationRepositoryInterface
{
    private $value;

    /**
     * @param $value
     */
    private function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param $value
     *
     * @return ConfigurationRepositoryStub
     */
    public static function returningValue($value)
    {
        return new ConfigurationRepositoryStub($value);
    }

    /**
     * @param $value
     */
    public function returnValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param string $paramKey
     *
     * @return mixed
     */
    public function getValue($paramKey)
    {
        return $this->value;
    }
}
