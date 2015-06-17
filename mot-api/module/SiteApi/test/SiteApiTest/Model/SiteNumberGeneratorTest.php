<?php

namespace SiteApiTest\Model;

use SiteApi\Model\SiteNumberGenerator;

/**
 * Tests the site number generator
 */
class SiteNumberGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /** @var SiteNumberGenerator $generator */
    private $generator;

    public function setup()
    {
        $this->generator = new SiteNumberGenerator();
    }

    public function testGenerateOk()
    {
        $result = $this->generator->generate(4);
        $this->assertEquals('S000004', $result);

        $result = $this->generator->generate(123);
        $this->assertEquals('S000123', $result);

        $result = $this->generator->generate(123456);
        $this->assertEquals('S123456', $result);

        $result = $this->generator->generate(12345678);
        $this->assertEquals('S12345678', $result);
    }

    public function testGenerateIdTooLongThrowsError()
    {
        $this->markTestSkipped(
            'On current moment in test db we have SiteId more than 7000000. '.
            'So before fixing problem in db I skip this test.'
        );

        $this->setExpectedException(\PHPUnit_Framework_Error_Warning::class);

        $result = $this->generator->generate(44444444444);
        $this->assertEquals('S44444444444', $result);
    }
}
