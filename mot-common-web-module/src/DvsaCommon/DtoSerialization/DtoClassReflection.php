<?php

namespace DvsaCommon\DtoSerialization;

use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\TypeCheck;

class DtoClassReflection
{
    private $class;

    /**
     * @var DtoPropertyReflection[]
     */
    private $properties;

    public function __construct($class, $properties)
    {
        TypeCheck::assertCollectionOfClass($properties, DtoPropertyReflection::class);

        $this->class = $class;
        $this->properties = $properties;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param $name
     * @return DtoPropertyReflection
     */
    public function getProperty($name)
    {
        return ArrayUtils::firstOrNull($this->properties,
            function (DtoPropertyReflection $reflection) use ($name) {
                return $name == $reflection->getName();
            });
    }

    public function getClass()
    {
        return $this->class;
    }
}
