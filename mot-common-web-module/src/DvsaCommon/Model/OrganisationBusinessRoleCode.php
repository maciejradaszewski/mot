<?php


namespace DvsaCommon\Model;


class OrganisationBusinessRoleCode
{

    /**
     * From entity OrganisationBusinessRoleCode
     */
    const AUTHORISED_EXAMINER_DESIGNATED_MANAGER = 'AEDM';
    const AUTHORISED_EXAMINER_DELEGATE = 'AED';
    const AUTHORISED_EXAMINER_PRINCIPAL = 'AEP';
    const SCHEME_MANAGER = 'DSM';

    /**
     * @return array of values for the type OrganisationBusinessRoleCode
     */
    public static function getAll()
    {
        return [
            self::AUTHORISED_EXAMINER_DESIGNATED_MANAGER,
            self::AUTHORISED_EXAMINER_DELEGATE,
            self::AUTHORISED_EXAMINER_PRINCIPAL,
            self::SCHEME_MANAGER,
        ];
    }

    /**
     * @param string $key a candidate RoleCode value
     *
     * @return true if $key is in the list of known values for the type
     */
    public static function exists($key)
    {
        return in_array($key, self::getAll(), true);
    }
}