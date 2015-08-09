<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModuleTest\Service;

use Dvsa\Mot\Frontend\AuthenticationModule\Service\GotoUrlService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\GotoUrlValidatorService;

class GotoUrlServiceTest extends \PHPUnit_Framework_TestCase
{
    const GOTO_URL = 'http://url.com/hello?query=param';

    /**
     * @var GotoUrlService
     */
    protected $gotoUrlService;

    /**
     * @var GotoUrlValidatorService
     */
    protected $gotoUrlValidatorServiceMock;

    public function setUp()
    {
        $this->gotoUrlValidatorServiceMock = $this->getMockBuilder(GotoUrlValidatorService::class)
            ->disableOriginalConstructor()
            ->setMethods(['isValid'])
            ->getMock();
    }

    public function testEncode_whenInvalid_shouldReturnEmptyString()
    {
        $this->gotoUrlValidatorServiceMock->expects($this->once())->method('isValid')->willReturn(false);
        $this->gotoUrlService = new GotoUrlService($this->gotoUrlValidatorServiceMock);

        $this->assertEquals('', $this->gotoUrlService->encodeGoto(self::GOTO_URL));
    }

    public function testEncodeAndDecodeAreSymmetrical()
    {
        $this->gotoUrlValidatorServiceMock->expects($this->any())->method('isValid')->willReturn(true);
        $this->gotoUrlService = new GotoUrlService($this->gotoUrlValidatorServiceMock);

        $this->assertEquals(self::GOTO_URL, $this->gotoUrlService->decodeGoto(
            $this->gotoUrlService->encodeGoto(self::GOTO_URL)
        ));
    }

    public function testdecode_whenInvalid_shouldReturnEmptyString()
    {
        $this->gotoUrlValidatorServiceMock->expects($this->once())->method('isValid')->willReturn(false);
        $this->gotoUrlService = new GotoUrlService($this->gotoUrlValidatorServiceMock);

        $this->assertEquals('', $this->gotoUrlService->decodeGoto(self::GOTO_URL));
    }
}
