<?php

namespace DvsaCommon\Model;

use DvsaCommon\Enum\CountryCode;

class CountryOfVts
{
    /**
     * Return an list of contry codes that VTS can be assigned to
     * @return array
     */
    public static function getPossibleCountryCodes()
    {
        return [
            CountryCode::ENGLAND,
            CountryCode::WALES,
            CountryCode::SCOTLAND,
        ];
    }
}
