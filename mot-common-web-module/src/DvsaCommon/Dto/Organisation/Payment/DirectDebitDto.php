<?php

namespace DvsaCommon\Dto\Organisation\Payment;

/**
 * Direct debit data.
 */
class DirectDebitDto
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $organisationId;

    /**
     * @var int
     */
    private $slots;

    /**
     * @var string datetime in simplified ISO format.
     */
    private $setupDate;

    /**
     * @var string date in Y-m-d format.
     */
    private $nextCollectionDate;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var string direct debit status from \DvsaCommon\Enum\DirectDebitStatusCode
     */
    private $statusCode;

    /**
     * @var string|null mandate token
     */
    private $mandateId;

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $nextCollectionDate
     * @return $this
     */
    public function setNextCollectionDate($nextCollectionDate)
    {
        $this->nextCollectionDate = $nextCollectionDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getNextCollectionDate()
    {
        return $this->nextCollectionDate;
    }

    /**
     * @param int $organisationId
     * @return $this
     */
    public function setOrganisationId($organisationId)
    {
        $this->organisationId = $organisationId;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrganisationId()
    {
        return $this->organisationId;
    }

    /**
     * @param float $amount
     * return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $setupDate
     * return $this
     */
    public function setSetupDate($setupDate)
    {
        $this->setupDate = $setupDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getSetupDate()
    {
        return $this->setupDate;
    }

    /**
     * @param int $slots
     * return $this
     */
    public function setSlots($slots)
    {
        $this->slots = $slots;
        return $this;
    }

    /**
     * @return int
     */
    public function getSlots()
    {
        return $this->slots;
    }

    /**
     * @param string $statusCode
     * return $this;
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param string $mandateId
     * @return $this
     */
    public function setMandateId($mandateId)
    {
        $this->mandateId = $mandateId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMandateId()
    {
        return $this->mandateId;
    }
}
