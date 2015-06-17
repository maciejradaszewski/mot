<?php

namespace DvsaCommon\Model;

use DvsaCommon\Enum\CountryOfRegistrationId;

class CountryOfRegistration
{
    /**
     * Return an id list, of all countries (available in the COR table) which are part of the United Kingdom
     * @return array
     */
    public static function getAllUkCountries()
    {
        return [
            CountryOfRegistrationId::GB_UK_ENG_CYM_SCO_UK_GREAT_BRITAIN,
            CountryOfRegistrationId::GB_NI_UK_NORTHERN_IRELAND
        ];
    }

    /**
     * To check if the given id is belong to a country withing the United Kingdom
     *
     * @param int $countryId
     * @return bool
     */
    public static function isUkCountry($countryId)
    {
        return in_array($countryId, self::getAllUkCountries());
    }
}
