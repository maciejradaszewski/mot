<?php

namespace DvsaCommon\Dto\Account;

use DvsaCommon\Dto\AbstractDataTransferObject;

/**
 * Account claim init data.
 */
class ClaimStartDto extends AbstractDataTransferObject
{
    /**
     * @var string users preset email
     */
    private $email;

    /**
     * @var int 6 digit pin generated for account
     */
    private $pin;

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return int
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * @param int $pin
     * @return $this
     */
    public function setPin($pin)
    {
        $this->pin = $pin;
        return $this;
    }
}
