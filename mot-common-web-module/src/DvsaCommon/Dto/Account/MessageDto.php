<?php

namespace DvsaCommon\Dto\Account;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;
use DvsaCommon\Dto\Person\PersonDto;

/**
 * Dto for message entity
 *
 * @package DvsaCommon\Dto\Account
 */
class MessageDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;

    /** @var  MessageTypeDto */
    private $type;
    /** @var  \DateTime */
    private $issuedDate;
    /** @var  \DateTime */
    private $expiryDate;
    /** @var  bool */
    private $isAcknowledged;
    /** @var  string */
    private $token;
    /** @var  \DvsaCommon\Dto\Person\PersonDto */
    private $person;

    /**
     * @return MessageTypeDto
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param MessageTypeDto $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }


    public function getIssuedDate()
    {
        return $this->issuedDate;
    }

    /**
     * @return $this
     */
    public function setIssuedDate($issuedDateTime)
    {
        $this->issuedDate = $issuedDateTime;
        return $this;
    }


    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @return $this
     */
    public function setExpiryDate($expiryDateTime)
    {
        $this->expiryDate = $expiryDateTime;
        return $this;
    }


    public function isAcknowledged()
    {
        return $this->isAcknowledged;
    }

    /**
     * @return $this
     */
    public function setIsAcknowledged($isAcknowledged)
    {
        $this->isAcknowledged = $isAcknowledged;
        return $this;
    }


    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return \DvsaCommon\Dto\Person\PersonDto
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param \DvsaCommon\Dto\Person\PersonDto $person
     * @return $this
     */
    public function setPerson($person)
    {
        $this->person = $person;
        return $this;
    }

    /**
     * Answers true if a PersonDto was set post-construction and prior to this call.
     * @return bool
     */
    public function hasPerson()
    {
        return $this->person != null && ($this->person instanceof PersonDto);
    }
}
