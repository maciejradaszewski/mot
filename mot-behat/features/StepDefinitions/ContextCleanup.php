<?php

use Behat\Behat\Context\Context;

/**
 * This is a workaround.
 *
 * Unfortunately there's a bug in Behat that causes context state to leak between scenario outline examples.
 * We need to do manual cleanups of state stored in contexts until https://github.com/Behat/Behat/issues/758 is fixed.
 */
class ContextCleanup
{
    /**
     * @param Context[] $contexts
     */
    public function cleanup(array $contexts)
    {
        foreach ($contexts as $context) {
            $this->cleanupContext($context);
        }
    }

    /**
     * Goes through all the properties that were not set via the constructor, and do not have
     * a "Context" in their name.
     * This heavily relies on conventions:
     *  * properties are named after constructor arguments
     *  * context property names use the "Context" suffix
     */
    private function cleanupContext(Context $context)
    {
        $reflection = new \ReflectionClass($context);

        $arguments = $this->getConstructorArguments($reflection);
        $properties = $this->filterOutConstructorAndContextProperties($reflection->getProperties(), $arguments);

        $this->cleanupProperties($reflection, $properties, $context);
    }

    /**
     * @param \ReflectionClass $reflection
     *
     * @return array
     */
    private function getConstructorArguments(\ReflectionClass $reflection)
    {
        return array_map(
            function ($property) {
                return $property->getName();
            },
            $reflection->getConstructor()->getParameters()
        );
    }

    /**
     * @param \ReflectionProperty[] $reflection
     * @param array                 $arguments
     *
     * @return \ReflectionProperty[]
     */
    private function filterOutConstructorAndContextProperties(array $properties, array $arguments)
    {
        return array_filter(
            $properties,
            function (\ReflectionProperty $property) use ($arguments) {
                return !in_array($property->getName(), $arguments) && false === strpos($property->getName(), 'Context');
            }
        );
    }

    /**
     * @param ReflectionClass       $reflection
     * @param \ReflectionProperty[] $properties
     * @param Context               $context
     */
    private function cleanupProperties(\ReflectionClass $reflection, array $properties, Context $context)
    {
        array_walk(
            $properties,
            function (\ReflectionProperty $property) use ($context, $reflection) {
                $property->setAccessible(true);
                $defaults = $reflection->getDefaultProperties();
                $value = array_key_exists($property->getName(), $defaults) ? $defaults[$property->getName()] : null;
                $property->setValue($context, $value);
            }
        );
    }
}