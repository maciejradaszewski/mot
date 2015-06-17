<?php

namespace ApplicationTest\Crypt\Hash;

use DvsaCommon\Crypt\Hash\BCryptHashFunction;

class BCryptHashFunctionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMinimumCostExceeded()
    {
        $func = (new BCryptHashFunction())->setCost(3);
        $func->hash("aaaaa");
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMaximumSecretLengthExceeded()
    {
        $func = (new BCryptHashFunction)->setCost(4);
        $func->hash(str_repeat('a', BCryptHashFunction::MAX_SECRET_LENGTH + 1));
    }

    public function testVerify()
    {
        $secret = 'a';
        $func = (new BCryptHashFunction)->setCost(4);
        $hash = $func->hash($secret);
        $this->assertTrue($func->verify($secret, $hash));
    }
}
