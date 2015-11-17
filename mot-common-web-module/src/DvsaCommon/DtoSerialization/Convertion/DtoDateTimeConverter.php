<?php

namespace DvsaCommon\DtoSerialization\Convertion;

use DvsaCommon\Date\DateUtils;
use DvsaCommon\DtoSerialization\DtoConverterInterface;
use DvsaCommon\Utility\TypeCheck;

class DtoDateTimeConverter implements DtoConverterInterface
{
    public function jsonToObject($json)
    {
        return new \DateTime($json);
    }

    /**
     * @param \DateTime $dateTime
     * @return array
     */
    public function objectToJson($dateTime)
    {
        TypeCheck::assertInstance($dateTime, \DateTime::class);

        return $dateTime->format(DateUtils::FORMAT_ISO_WITH_TIME);
    }
}
