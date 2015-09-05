<?php

namespace DvsaCommon\Dto\Common;

use DvsaCommon\Dto\AbstractDataTransferObject;

class MotTestRecentCertificatesDto extends AbstractDataTransferObject
{

    /** @var int */
    private $id;
    /** @var int */
    private $testerId;
    /** @var int */
    private $vtsId;
    /** @var int */
    private $prsId;
    /** @var string */
    private $registration;
    /** @var string */
    private $make;
    /** @var string */
    private $model;
    /** @var string */
    private $vin;
    /** @var string */
    private $motTestResult;
    /** @var string */
    private $certificateStatus;
    /** @var string */
    private $statusCode;
    /** @var string */
    private $certificateStorageKey;
    /** @var DateTime */
    private $generationCompletedOn;
    /**  @var string */
    private $recipientFirstName;
    /** @var string */
    private $recipientFamilyName;
    /** @var string */
    private $recipientEmailAddress;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getTesterId()
    {
        return $this->testerId;
    }

    /**
     * @param int $testerId
     */
    public function setTesterId($testerId)
    {
        $this->testerId = $testerId;
    }

    /**
     * @return int
     */
    public function getVtsId()
    {
        return $this->vtsId;
    }

    /**
     * @param int $prsId
     */
    public function setPrsId($prsId)
    {
        $this->prsId = $prsId;
    }

    /**
     * @return int
     */
    public function getPrsId()
    {
        return $this->prsId;
    }

    /**
     * @param int $vtsId
     */
    public function setVtsId($vtsId)
    {
        $this->vtsId = $vtsId;
    }

    /**
     * @return string
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * @param string $registration
     */
    public function setRegistration($registration)
    {
        $this->registration = $registration;
    }

    /**
     * @return string
     */
    public function getMake()
    {
        return $this->make;
    }

    /**
     * @param string $make
     */
    public function setMake($make)
    {
        $this->make = $make;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getVin()
    {
        return $this->vin;
    }

    /**
     * @param string $vin
     */
    public function setVin($vin)
    {
        $this->vin = $vin;
    }

    /**
     * @return string
     */
    public function getMotTestResult()
    {
        return $this->motTestResult;
    }

    /**
     * @param string $motTestResult
     */
    public function setMotTestResult($motTestResult)
    {
        $this->motTestResult = $motTestResult;
    }

    /**
     * @return string
     */
    public function getCertificateStatus()
    {
        return $this->certificateStatus;
    }

    /**
     * @param string $certificateStatus
     */
    public function setCertificateStatus($certificateStatus)
    {
        $this->certificateStatus = $certificateStatus;
    }

    /**
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param string $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return string
     */
    public function getCertificateStorageKey()
    {
        return $this->certificateStorageKey;
    }

    /**
     * @param string $certificateStorageKey
     */
    public function setCertificateStorageKey($certificateStorageKey)
    {
        $this->certificateStorageKey = $certificateStorageKey;
    }

    /**
     * @return DateTime
     */
    public function getGenerationCompletedOn()
    {
        return $this->generationCompletedOn;
    }

    /**
     * @param timestamp $generationCompletedOn
     */
    public function setGenerationCompletedOn($generationCompletedOn)
    {
        $this->generationCompletedOn = $generationCompletedOn;
    }

    /**
     * @return string
     */
    public function getRecipientFirstName()
    {
        return $this->recipientFirstName;
    }

    /**
     * @param string $recipientFirstName
     * @return $this
     */
    public function setRecipientFirstName($recipientFirstName)
    {
        $this->recipientFirstName = $recipientFirstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecipientFamilyName()
    {
        return $this->recipientFamilyName;
    }

    /**
     * @param string $recipientFamilyName
     * @return $this
     */
    public function setRecipientFamilyName($recipientFamilyName)
    {
        $this->recipientFamilyName = $recipientFamilyName;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecipientEmailAddress()
    {
        return $this->recipientEmailAddress;
    }

    /**
     * @param string $recipientEmailAddress
     * @return $this
     */
    public function setRecipientEmailAddress($recipientEmailAddress)
    {
        $this->recipientEmailAddress = $recipientEmailAddress;

        return $this;
    }
}
