<?php

namespace DvsaCommon\Dto\Common;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;
use DvsaCommon\Enum\LanguageTypeCode;

/**
 * Class ReasonForCancelDto
 */
class ReasonForCancelDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;

    /** @var string */
    private $reason;
    /** @var string */
    private $reasonCy;
    /** @var boolean */
    private $isAbandoned;

    /** @var boolean */
    private $isDisplayable;

   /**
     * @param string $reason
     *
     * @return ReasonForCancelDto
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     *
     * @return ReasonForCancelDto
     */
    public function setReasonCy($reason)
    {
        $this->reasonCy = $reason;

        return $this;
    }

    /**
     * @return string
     */
    public function getReasonCy()
    {
        return $this->reasonCy;
    }

    /**
     * Return reason in specified language
     *
     * @param string $lang Interface language
     *
     * @return string
     */
    public function getReasonInLang($lang = LanguageTypeCode::ENGLISH)
    {
        if ($lang === LanguageTypeCode::WELSH) {
            return $this->getReasonCy();
        }

        return $this->getReason();
    }

    /**
     * @param bool $isAbandoned
     *
     * @return ReasonForCancelDto
     */
    public function setAbandoned($isAbandoned)
    {
        $this->isAbandoned = $isAbandoned;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getAbandoned()
    {
        return $this->isAbandoned;
    }

    public function setIsDisplayable($isUsedInMot2)
    {
        $this->isDisplayable = $isUsedInMot2;
    }

    public function getIsDisplayable()
    {
        return $this->isDisplayable;
    }
}
