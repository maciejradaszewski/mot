<?php

namespace DvsaCommonApiTest\Obfuscate\Factory;

use DvsaCommon\Obfuscate\Factory\ParamObfuscatorFactory;
use DvsaCommon\Obfuscate\ParamEncrypter;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use Zend\ServiceManager\ServiceLocatorInterface;

class ParamObfuscatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    public function testFactory()
    {
        $mockServiceLocator = XMock::of(ServiceLocatorInterface::class, ['get']);

        $this->mockMethod(
            $mockServiceLocator, 'get', $this->at(0), XMock::of(ParamEncrypter::class), ParamEncrypter::class
        );
        $this->mockMethod($mockServiceLocator, 'get', $this->at(1), [], 'config');

        //  --   Create the factory --
        $factory = new ParamObfuscatorFactory();
        $result = $factory->createService($mockServiceLocator);

        //  --  check   --
        $this->assertInstanceOf(ParamObfuscator::class, $result);
    }
}
