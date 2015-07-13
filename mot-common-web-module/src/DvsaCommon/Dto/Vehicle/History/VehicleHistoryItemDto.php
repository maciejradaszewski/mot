<?php

namespace DvsaCommon\Dto\Vehicle\History;

use DvsaCommon\Date\DateUtils;
use DvsaCommon\Enum\MotTestStatusName;

class VehicleHistoryItemDto
{
    const DISPLAY_PASS_STATUS_VALUE = 'Pass';
    const DISPLAY_FAIL_STATUS_VALUE = 'Fail';
    const DISPLAY_ABAN_STATUS_VALUE = 'Abandoned';

    const HISTORY_ITEM_DATE_FORMAT = 'j F Y';

    /** @var int $id */
    private $id;

    /** @var string $status */
    private $status;

    /** @var string $issuedDate */
    private $issuedDate;

    /** @var \DateTime $expiryDate */
    private $expiryDate;

    /** @var string $motTestNumber */
    private $motTestNumber;

    /** @var string $testType */
    private $testType;

    /** @var VehicleHistoryItemSiteDto $site */
    private $site;

    /** @var bool $allowEdit */
    private $allowEdit;

    /** @var string $prsMotTestId */
    private $prsMotTestId;

    public function __construct()
    {
        $this->site = new VehicleHistoryItemSiteDto();
    }

    /**
     * @return bool
     */
    public function hasPassed()
    {
        return $this->status === MotTestStatusName::PASSED;
    }

    /**
     * @return string
     */
    public function getDisplayStatus()
    {
        $displayStatuses = [
            MotTestStatusName::PASSED => self::DISPLAY_PASS_STATUS_VALUE,
            MotTestStatusName::FAILED => self::DISPLAY_FAIL_STATUS_VALUE,
            MotTestStatusName::ABANDONED => self::DISPLAY_ABAN_STATUS_VALUE,
        ];

        return isset($displayStatuses[$this->status])
            ? $displayStatuses[$this->status]
            : '';
    }

    /**
     * @return string
     */
    public function getDisplayIssuedDate()
    {
        $issuedDate = 'n/a';

        if ($this->issuedDate) {
            $issuedDate = date(self::HISTORY_ITEM_DATE_FORMAT, strtotime($this->issuedDate));
        }

        return $issuedDate;
    }

    public function getSiteId()
    {
        return $this->site->getId();
    }

    public function getSiteName()
    {
        return $this->site->getName();
    }

    public function getSiteAddress()
    {
        return $this->site->getAddress();
    }

    /**
     * @return boolean
     */
    public function isAllowEdit()
    {
        return $this->allowEdit;
    }

    /**
     * @param boolean $allowEdit
     */
    public function setAllowEdit($allowEdit)
    {
        $this->allowEdit = $allowEdit;
    }

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
     * @return string
     */
    public function getIssuedDate()
    {
        return $this->issuedDate;
    }

    /**
     * @param string $issuedDate
     */
    public function setIssuedDate($issuedDate)
    {
        $this->issuedDate = $issuedDate;
    }

    /**
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @param string $expiryDate
     */
    public function setExpiryDate($expiryDate)
    {
        if ($expiryDate && !$expiryDate instanceof \DateTime) {
            $expiryDate = DateUtils::toDateTime($expiryDate);
        }

        $this->expiryDate = $expiryDate;
    }

    /**
     * @return string
     */
    public function getMotTestNumber()
    {
        return $this->motTestNumber;
    }

    /**
     * @param string $motTestNumber
     */
    public function setMotTestNumber($motTestNumber)
    {
        $this->motTestNumber = $motTestNumber;
    }

    /**
     * @return VehicleHistoryItemSiteDto
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param VehicleHistoryItemSiteDto $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getTestType()
    {
        return $this->testType;
    }

    /**
     * @param string $testType
     */
    public function setTestType($testType)
    {
        $this->testType = $testType;
    }

    /**
     * @return string
     */
    public function getPrsMotTestId()
    {
        return $this->prsMotTestId;
    }

    /**
     * @param string $prsMotTestId
     *
     * @return $this
     */
    public function setPrsMotTestId($prsMotTestId)
    {
        $this->prsMotTestId = $prsMotTestId;

        return $this;
    }
}
