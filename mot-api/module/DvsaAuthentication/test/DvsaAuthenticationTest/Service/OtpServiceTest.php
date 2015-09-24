<?php
namespace DvsaAuthenticationTest\Service;

use DvsaAuthentication\Service\OtpService;
use DvsaAuthentication\Service\OtpServiceAdapter;

class OtpServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testItCallsTheAdapterToAuthenticate()
    {
        $adapter = $this->getMock(OtpServiceAdapter::class);
        $adapter->expects($this->once())
            ->method('authenticate')
            ->with('123456');

        $otpService = new OtpService($adapter);

        $otpService->authenticate('123456');
    }
}