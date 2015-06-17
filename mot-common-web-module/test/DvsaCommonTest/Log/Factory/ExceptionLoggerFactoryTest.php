<?php

namespace DvsaCommonTest\Log\Factory;


use DvsaCommon\Log\Factory\ExceptionLoggerFactory;


class ExceptionLoggerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The config key logAppName needs to be defined in the application config
     * for this logger to be created.
     */
    public function testExceptionIsThrownIfNoAppNameIsSpecified()
    {
        $this->setExpectedException(
            \RuntimeException::class,
            'The logAppName config key is not set'
        );

        $mock = $this->getMock(\Zend\ServiceManager\ServiceManager::class, ['get']);
        $mock->expects($this->once())
             ->method('get')
             ->will($this->returnValue([]));

        $factory = new ExceptionLoggerFactory();
        $factory->createService($mock);
    }

    public function testLoggerCreatedInstanceOfLogger()
    {
        $mock = $this->getMock(\Zend\ServiceManager\ServiceManager::class, ['get']);
        $mock->expects($this->once())
            ->method('get')
            ->will($this->returnValue(['logAppName' => 'foo']));

        $factory = new ExceptionLoggerFactory();
        $logger = $factory->createService($mock);

        $this->assertInstanceOf(\Zend\Log\Logger::class, $logger);
    }
}