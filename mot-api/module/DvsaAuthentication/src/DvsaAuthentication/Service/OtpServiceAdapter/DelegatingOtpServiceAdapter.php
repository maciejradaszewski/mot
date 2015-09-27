<?php

namespace DvsaAuthentication\Service\OtpServiceAdapter;

use DvsaAuthentication\Service\OtpServiceAdapter;
use DvsaEntities\Entity\Person;

class DelegatingOtpServiceAdapter implements OtpServiceAdapter
{
    /**
     * @var OtpServiceAdapter
     */
    private $pinOtpServiceAdapter;

    /**
     * @var OtpServiceAdapter
     */
    private $cardOtpServiceAdapter;

    /**
     * @param OtpServiceAdapter $pinOtpServiceAdapter
     * @param OtpServiceAdapter $cardOtpServiceAdapter
     */
    public function __construct(OtpServiceAdapter $pinOtpServiceAdapter, OtpServiceAdapter $cardOtpServiceAdapter)
    {
        $this->pinOtpServiceAdapter = $pinOtpServiceAdapter;
        $this->cardOtpServiceAdapter = $cardOtpServiceAdapter;
    }

    /**
     * @param Person $person
     * @param string $token
     *
     * @return bool
     */
    public function authenticate(Person $person, $token)
    {
        if ($person->getAuthenticationMethod()->isCard()) {
            return $this->cardOtpServiceAdapter->authenticate($person, $token);
        }

        return $this->pinOtpServiceAdapter->authenticate($person, $token);
    }
}