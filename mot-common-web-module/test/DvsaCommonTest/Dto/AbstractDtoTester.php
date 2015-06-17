<?php

namespace DvsaCommonTest\Dto;

/**
 * Abstract entity tester
 */
class AbstractDtoTester extends \PHPUnit_Framework_TestCase
{
    protected $dtoClassName;

    protected $testMethods = [];

    /**
     * @dataProvider providerGettersAndSetters
     */
    public function testGettersAndSetters($methodName, $expectValue, $isIsMethod = false)
    {
        $dto = new $this->dtoClassName();

        $dto->{'set' . $methodName}($expectValue);
        $actualResult = $dto->{$isIsMethod ? lcfirst($methodName) : 'get' . $methodName}();

        $this->assertSame($expectValue, $actualResult);
    }

    /**
     * @return array
     */
    public function providerGettersAndSetters()
    {
        $reflection = new \ReflectionClass($this->dtoClassName);

        $methods = $reflection->getMethods();

        foreach ($methods as $method) {
            if (substr($method->getName(), 0, 3) == 'set') {
                $methodName = substr($method->getName(), 3);

                $isIsMethod = ($reflection->hasMethod(lcfirst($methodName)) && substr($methodName, 0, 2) === 'Is');

                if ((ltrim($method->getDeclaringClass()->getName(), "\\") == ltrim($this->dtoClassName, "\\"))
                    && $method->isPublic()
                    && $reflection->hasProperty(lcfirst($methodName))
                    && ($isIsMethod || $reflection->hasMethod('get' . $methodName))
                ) {
                    // If this $parameter->getClass() is not null, one of the methods is type-hinted.
                    /** @var \ReflectionParameter $firstParam */
                    $firstParam = current($method->getParameters());

                    if ($firstParam->getClass() !== null
                        || $method->getNumberOfRequiredParameters() > 1) {
                        continue;
                    }

                    $testValue = $methodName . '_test_' . rand(10000, 200000);

                    if ($methodName == 'Id') {
                        $testValue = rand(10000, 200000);
                    } elseif ($firstParam->isArray()) {
                        $testValue = [$testValue];
                    }

                    $this->testMethods[$methodName] = [$methodName, $testValue, $isIsMethod];
                }
            }
        }

        return $this->testMethods;
    }
}
