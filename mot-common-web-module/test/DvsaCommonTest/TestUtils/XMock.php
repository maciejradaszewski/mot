<?php

namespace DvsaCommonTest\TestUtils;

use ReflectionClass;
use ReflectionMethod;

/**
 * Provides e(X)tra mock functionalities that are a drag to do with vanilla phpunit
 *
 * Class XMock
 *
 * @package DvsaCommonApiTest\Utils
 */
class XMock
{
    /**
     * Creates a mock that has original constructor disabled,
     * which is the case in almost all scenarios
     *
     * @param            $classname
     * @param array|null $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     * @throws \Exception
     */
    public static function of($classname, $methods = null)
    {
        $isInterface = interface_exists($classname);
        $isTrait = trait_exists($classname);
        if (!(class_exists($classname) || $isInterface || $isTrait)) {
            throw new \Exception("Class or interface or trait $classname does not exist or could not be loaded!");
        }

        $mock = BacktraceTestCaseFinder::find()->getMockBuilder($classname)->disableOriginalConstructor();
        if (!empty($methods)) {
            $mock->setMethods($methods);
        }

        if ($isInterface) {
            return $mock->getMockForAbstractClass();
        } elseif ($isTrait) {
            return $mock->getMockForTrait();
        }

        return $mock->getMock();
    }

    public static function mockClassField($instance, $fieldName, $fieldValue, $className = null)
    {
        $r = new ReflectionClass($className ? $className : get_class($instance));

        $prop = $r->getProperty($fieldName);
        $prop->setAccessible(true);
        $prop->setValue($instance, $fieldValue);

    }

    /**
     * Return protected method of class
     *
     * @param string $className
     * @param string $methodName
     *
     * @return ReflectionMethod
     */
    public static function getMethod($className, $methodName)
    {
        $method = new ReflectionMethod(
            $className, $methodName
        );

        $method->setAccessible(true);

        return $method;
    }

    /**
     * Call protected method of Object
     *
     * @param object $instance   Instance of Object
     * @param string $methodName Call method with name
     * @param array  $args       Method arguments
     * @param string $className  Name of Object
     *
     * @return mixed
     */
    public static function invokeMethod($instance, $methodName, array $args = [], $className = null)
    {
        $methodMock = self::getMethod(
            $className ? $className : get_class($instance),
            $methodName
        );

        return $methodMock->invokeArgs($instance, $args);
    }
}
