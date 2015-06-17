<?php

namespace DvsaMCommonTest\Factory\Validator;

use DvsaCommon\Factory\Validator\UsernameValidatorFactory;
use DvsaCommon\Validator\UsernameValidator;
use DvsaCommonTest\Bootstrap;

/**
 * Class UsernameValidatorFactoryTest.
 */
class UsernameValidatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryReturnsUsernameValidatorInstance()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $factory        = new UsernameValidatorFactory();

        $this->assertInstanceOf(UsernameValidator::class, $factory->createService($serviceManager));
    }
}
