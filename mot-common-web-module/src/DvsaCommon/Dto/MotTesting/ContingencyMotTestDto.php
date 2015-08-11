<?php

namespace DvsaCommon\Dto\MotTesting;

use DvsaCommon\Dto\AbstractDataTransferObject;

/**
 * Represents an in-progress contingency test
 */
class ContingencyMotTestDto extends AbstractDataTransferObject
{
    private $testedByWhom;

    private $siteId;

    private $contingencyCode;

    private $performedAt;

    private $dateYear;

    private $dateMonth;

    private $dateDay;

    private $reasonCode;

    private $reasonText;

    private $testerCode;

    /**
     * @return string
     */
    public function getTesterCode()
    {
        return $this->testerCode;
    }

    /**
     * @param string $testerCode
     */
    public function setTesterCode($testerCode)
    {
        $this->testerCode = $testerCode;
    }

    /**
     * @return string
     */
    public function getContingencyCode()
    {
        return $this->contingencyCode;
    }

    /**
     * @param mixed $contingencyCode
     * @return ContingencyMotTestDto
     */
    public function setContingencyCode($contingencyCode)
    {
        $this->contingencyCode = $contingencyCode;
        return $this;
    }

    /**
     * @return String ISO_8601 date
     */
    public function getPerformedAt()
    {
        return $this->performedAt;
    }

    /**
     * @param string $performedAt
     * @return ContingencyMotTestDto
     */
    public function setPerformedAt($performedAt)
    {
        $this->performedAt = $this->filterDateFormat($performedAt);
        return $this;
    }

    /**
     * @return string
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * @param string $reason
     * @return ContingencyMotTestDto
     */
    public function setReasonCode($reason)
    {
        $this->reasonCode = $reason;
        return $this;
    }

    /**
     * @return string
     */
    public function getReasonText()
    {
        return $this->reasonText;
    }

    /**
     * @param string $reason
     * @return ContingencyMotTestDto
     */
    public function setReasonText($reason)
    {
        $this->reasonText = trim($reason);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @param mixed $site
     *
     * @return ContingencyMotTestDto
     */
    public function setSiteId($site)
    {
        $this->siteId = $site;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTestedByWhom()
    {
        return $this->testedByWhom;
    }

    /**
     * @param mixed $testedByWhom
     * @return ContingencyMotTestDto
     */
    public function setTestedByWhom($testedByWhom)
    {
        $this->testedByWhom = $testedByWhom;
        return $this;
    }

    /**
     * @return string
     */
    public function getDateYear()
    {
        return $this->dateYear;
    }

    /**
     * @param string $dateYear
     * @return ContingencyMotTestDto
     */
    public function setDateYear($dateYear)
    {
        $this->dateYear = $dateYear;
        return $this;
    }

    /**
     * @return string
     */
    public function getDateMonth()
    {
        return $this->dateMonth;
    }

    /**
     * @param string $dateMonth
     * @return ContingencyMotTestDto
     */
    public function setDateMonth($dateMonth)
    {
        $this->dateMonth = $dateMonth;
        return $this;
    }

    /**
     * @return string
     */
    public function getDateDay()
    {
        return $this->dateDay;
    }

    /**
     * @param string $dateDay
     * @return ContingencyMotTestDto
     */
    public function setDateDay($dateDay)
    {
        $this->dateDay = $dateDay;
        return $this;
    }

    /**
     * @desc Filter date string to be sure that it's in correct format
     *
     * @param string $date
     * @return string
     */
    protected function filterDateFormat($date)
    {
        $dateTime = \DateTime::createFromFormat('Y-m-d', $date);
        if ($dateTime instanceof \DateTime) {
            return $dateTime->setTime(0, 0, 0)->format('Y-m-d');
        } else {
            return $date;
        }
    }
}
