<?php

namespace DvsaCommon\Dto\Common;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;
use DvsaCommon\Enum\LanguageTypeCode;

/**
 * Class ReasonForRefusalDto
 */
class ReasonForRefusalDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;

    /** @var string */
    private $reason;
    /** @var string */
    private $reasonCy;

    /**
     * @param string $reason
     *
     * @return ReasonForRefusalDto
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
     * @return ReasonForRefusalDto
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
}
