<?php

namespace DvsaAuthentication\Service;

class OtpService
{
    /**
     * @var OtpServiceAdapter
     */
    private $otpServiceAdapter;

    /**
     * @param OtpServiceAdapter $otpServiceAdapter
     */
    public function __construct(OtpServiceAdapter $otpServiceAdapter)
    {
        $this->otpServiceAdapter = $otpServiceAdapter;
    }

    /**
     * @param string $token
     */
    public function authenticate($token)
    {
        $this->otpServiceAdapter->authenticate($token);
    }
}
