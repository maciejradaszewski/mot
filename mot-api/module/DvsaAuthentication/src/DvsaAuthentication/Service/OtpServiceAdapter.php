<?php

namespace DvsaAuthentication\Service;

interface OtpServiceAdapter
{
    /**
     * @param string $token
     *
     * @throws OtpException if authentication fails
     */
    public function authenticate($token);
}