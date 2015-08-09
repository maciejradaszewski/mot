<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModuleTest\Service;

use Dvsa\Mot\Frontend\AuthenticationModule\Service\GotoUrlValidatorService;

class GotoUrlValidatorServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected $validDomains = [];

    /**
     * @var GotoUrlValidatorService
     */
    protected $gotoUrlValidatorService;

    public function setUp()
    {
        $this->validDomains = ['mot.gov.uk'];
        $this->gotoUrlValidatorService = new GotoUrlValidatorService($this->validDomains);
    }

    /**
     * @dataProvider getValidDomains
     */
    public function testIsValid($domain)
    {
        $this->assertTrue($this->gotoUrlValidatorService->isValid($domain));
    }

    /**
     * @dataProvider getInvalidDomains
     */
    public function testIsInvalid($domain)
    {
        $this->assertFalse($this->gotoUrlValidatorService->isValid($domain));
    }

    public function getValidDomains()
    {
        return [
            [
                'http://mysite.mot.gov.uk',
                'http://mot.gov.uk',
                'http://mot-web-frontend.mot.gov.uk',
            ],
        ];
    }

    public function getInvalidDomains()
    {
        return [
            [
                'http://invalid1.mot.com',
                'http://invalid2.mot-web-frontend.gov',
            ],
        ];
    }
}
