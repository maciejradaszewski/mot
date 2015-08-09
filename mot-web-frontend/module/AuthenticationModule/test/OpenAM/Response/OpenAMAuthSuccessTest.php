<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModuleTest\OpenAM\Response;

use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthSuccess;

class OpenAMAuthSuccessTest extends \PHPUnit_Framework_TestCase
{
    const TOKEN = 'token';

    public function testGetToken()
    {
        $authSuccess = new OpenAMAuthSuccess(self::TOKEN);
        $this->assertEquals(self::TOKEN, $authSuccess->getToken());
    }

    public function testIsSuccessMethodReturnsTrue()
    {
        $authSuccess = new OpenAMAuthSuccess(self::TOKEN);
        $this->assertTrue($authSuccess->isSuccess());
    }
}
