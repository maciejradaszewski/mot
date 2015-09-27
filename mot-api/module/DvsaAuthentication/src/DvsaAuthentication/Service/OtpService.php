<?php

namespace DvsaAuthentication\Service;

use DvsaAuthentication\Service\Exception\OtpException;

class OtpService
{
    /**
     * @var OtpServiceAdapter
     */
    private $otpServiceAdapter;

    /**
     * @var OtpFailedAttemptCounter
     */
    private $otpFailedAttemptCounter;

    /**
     * @var PersonProvider
     */
    private $personProvider;

    /**
     * @param OtpServiceAdapter       $otpServiceAdapter
     * @param OtpFailedAttemptCounter $otpFailedAttemptCounter
     * @param PersonProvider          $personProvider
     */
    public function __construct(
        OtpServiceAdapter $otpServiceAdapter,
        OtpFailedAttemptCounter $otpFailedAttemptCounter,
        PersonProvider $personProvider
    ) {
        $this->otpServiceAdapter = $otpServiceAdapter;
        $this->otpFailedAttemptCounter = $otpFailedAttemptCounter;
        $this->personProvider = $personProvider;
    }

    /**
     * @param string $token
     */
    public function authenticate($token)
    {
        $maxAttempts = $this->otpFailedAttemptCounter->getMaxAttempts();

        if (empty($token)) {
            throw new OtpException($maxAttempts, $maxAttempts);
        }

        $person = $this->personProvider->getPerson();

        if (!$this->otpServiceAdapter->authenticate($person, $token)) {
            $this->otpFailedAttemptCounter->attemptFailed($person);

            throw new OtpException($maxAttempts, $this->otpFailedAttemptCounter->getLeftAttempts($person));
        }

        $this->otpFailedAttemptCounter->attemptSucceeded($person);
    }
}
