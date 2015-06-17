<?php

namespace DvsaCommonApiTest\Obfuscate;

use DvsaCommon\Obfuscate\ParamEncoder;

class ParamEncoderTest extends \PHPUnit_Framework_TestCase {

    /** @var ParamEncoder $paramEncoder */
    private $paramEncoder;

    public function setUp()
    {
        $this->paramEncoder = new ParamEncoder();
    }

    public function testPassingParamForEncodingWillNotReturnSameResult()
    {
        $param = 'test';

        $encode = $this->paramEncoder->encode($param);

        $this->assertNotEmpty($encode);
        $this->assertNotEquals($encode, $param);
    }

    public function testPassingParamForDecodingWillNotReturnSameResult()
    {
        $param = 'dGVzdA%3D%3D';

        $decode = $this->paramEncoder->decode($param);

        $this->assertNotEmpty($decode);
        $this->assertNotEquals($decode, $param);
    }

}
 