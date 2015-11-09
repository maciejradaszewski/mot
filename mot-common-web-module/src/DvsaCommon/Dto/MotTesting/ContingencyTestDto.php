<?php
/**
 * This file is part of the DVSA MOT Common project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommon\Dto\MotTesting;

use DateTimeImmutable;
use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\JsonUnserializable;
use JsonSerializable;

/**
 * Represents an in-progress contingency test.
 */
class ContingencyTestDto extends AbstractDataTransferObject implements JsonSerializable, JsonUnserializable
{
    const DATETIME_FORMAT = 'Y-m-d g:ia';

    /**
     * @var string
     */
    private $siteId;

    /**
     * @var DateTimeImmutable
     */
    private $performedAt;

    /**
     * @var string
     */
    private $reasonCode;

    /**
     * @var string
     */
    private $otherReasonText;

    /**
     * @var string
     */
    private $contingencyCode;

    /**
     * @param mixed $site
     *
     * @return ContingencyTestDto
     */
    public function setSiteId($site)
    {
        $this->siteId = $site;

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
     * @param DateTimeImmutable|null $performedAt
     *
     * @return $this
     */
    public function setPerformedAt(DateTimeImmutable $performedAt = null)
    {
        $this->performedAt = $performedAt;

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getPerformedAt()
    {
        return $this->performedAt;
    }

    /**
     * Get year component of the performedAt property (full numeric representation, 4 digits).
     *
     * @return string|null
     */
    public function getPerformedAtYear()
    {
        return $this->performedAt ? $this->performedAt->format('Y') : null;
    }

    /**
     * Get month component of the performedAt property (numeric representation of the month with leading zeros).
     *
     * @return string|null
     */
    public function getPerformedAtMonth()
    {
        return $this->performedAt ? $this->performedAt->format('m') : null;
    }

    /**
     * Get day component of the performedAt property (day of the month, with leading zeros).
     *
     * @return string|null
     */
    public function getPerformedAtDay()
    {
        return $this->performedAt ? $this->performedAt->format('d') : null;
    }

    /**
     * Get hour component of the performedAt property (12-hour format without leading zeros).
     *
     * @return string|null
     */
    public function getPerformedAtHour()
    {
        return $this->performedAt ? $this->performedAt->format('g') : null;
    }

    /**
     * Get minute component of the performedAt property (with leading zeros).
     *
     * @return string|null
     */
    public function getPerformedAtMinute()
    {
        return $this->performedAt ? $this->performedAt->format('i') : null;
    }

    /**
     * Get Ante/Post meridiem component of the performedAt property ("am" or "pm").
     *
     * @return string|null
     */
    public function getPerformedAtAmPm()
    {
        return $this->performedAt ? $this->performedAt->format('a') : null;
    }

    /**
     * @param string $reason
     *
     * @return ContingencyTestDto
     */
    public function setReasonCode($reason)
    {
        $this->reasonCode = $reason;

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
     * @param string $otherReasonText
     *
     * @return ContingencyTestDto
     */
    public function setOtherReasonText($otherReasonText)
    {
        $this->otherReasonText = trim($otherReasonText);

        return $this;
    }

    /**
     * @return string
     */
    public function getOtherReasonText()
    {
        return $this->otherReasonText;
    }

    /**
     * @param mixed $contingencyCode
     *
     * @return ContingencyTestDto
     */
    public function setContingencyCode($contingencyCode)
    {
        $this->contingencyCode = $contingencyCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getContingencyCode()
    {
        return $this->contingencyCode;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'siteId'            => $this->getSiteId(),
            'performedAtYear'   => $this->getPerformedAtYear(),
            'performedAtMonth'  => $this->getPerformedAtMonth(),
            'performedAtDay'    => $this->getPerformedAtDay(),
            'performedAtHour'   => $this->getPerformedAtHour(),
            'performedAtMinute' => $this->getPerformedAtMinute(),
            'performedAtAmPm'   => $this->getPerformedAtAmPm(),
            'reasonCode'        => $this->getReasonCode(),
            'otherReasonText'   => $this->getOtherReasonText(),
            'contingencyCode'   => $this->getContingencyCode(),
       ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonUnserialize(array $data)
    {
        $this->setSiteId(isset($data['siteId']) ? $data['siteId'] : null);
        if (isset($data['performedAtYear']) && isset($data['performedAtMonth']) && isset($data['performedAtDay'])
            && isset($data['performedAtHour']) && isset($data['performedAtMinute']) && isset($data['performedAtAmPm'])) {
            $this->setPerformedAt(DateTimeImmutable::createFromFormat(self::DATETIME_FORMAT, sprintf('%s-%s-%s %s:%s%s',
                $data['performedAtYear'], $data['performedAtMonth'], $data['performedAtDay'],
                $data['performedAtHour'], $data['performedAtMinute'], $data['performedAtAmPm'])));
        } else {
            $this->setPerformedAt(null);
        }
        $this->setReasonCode(isset($data['reasonCode']) ? $data['reasonCode'] : null);
        $this->setOtherReasonText(isset($data['otherReasonText']) ? $data['otherReasonText'] : null);
        $this->setContingencyCode(isset($data['contingencyCode']) ? $data['contingencyCode'] : null);
    }
}
