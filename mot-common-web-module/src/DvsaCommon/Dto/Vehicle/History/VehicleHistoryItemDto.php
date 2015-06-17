<?php

namespace DvsaCommon\Dto\Vehicle\History;

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
    /** @var string $motTestNumber */
    private $motTestNumber;
    /** @var string $testType */
    private $testType;
    /** @var $site VehicleHistoryItemSiteDto */
    private $site;
    /** @var bool $allowEdit */
    private $allowEdit;

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
        $displayStatus = '';

        switch ($this->status) {
            case MotTestStatusName::PASSED:
                $displayStatus = self::DISPLAY_PASS_STATUS_VALUE;

                break;
            case MotTestStatusName::FAILED:
                $displayStatus = self::DISPLAY_FAIL_STATUS_VALUE;

                break;
            case MotTestStatusName::ABANDONED:
                $displayStatus = self::DISPLAY_ABAN_STATUS_VALUE;

                break;
        }

        return $displayStatus;
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
}
