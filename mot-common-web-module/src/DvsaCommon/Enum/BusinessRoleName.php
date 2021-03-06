<?php

namespace DvsaCommon\Enum;

/**
 * Enum class generated from the 'person_system_role' table
 *
 * DO NOT EDIT! -- THIS CLASS IS GENERATED BY mot-common-web-module/generate_enums.php
 * @codeCoverageIgnore
 */
class BusinessRoleName
{
    const USER = 'USER';
    const VEHICLE_EXAMINER = 'VEHICLE-EXAMINER';
    const DVSA_SCHEME_MANAGEMENT = 'DVSA-SCHEME-MANAGEMENT';
    const DVSA_SCHEME_USER = 'DVSA-SCHEME-USER';
    const DVSA_AREA_OFFICE_1 = 'DVSA-AREA-OFFICE-1';
    const FINANCE = 'FINANCE';
    const CUSTOMER_SERVICE_MANAGER = 'CUSTOMER-SERVICE-MANAGER';
    const CUSTOMER_SERVICE_CENTRE_OPERATIVE = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE';
    const CRON = 'CRON';
    const DVLA_OPERATIVE = 'DVLA-OPERATIVE';
    const DVSA_AREA_OFFICE_2 = 'DVSA-AREA-OFFICE-2';
    const GVTS_TESTER = 'GVTS-TESTER';
    const VM_10519_USER = 'VM-10519-USER';
    const DVLA_MANAGER = 'DVLA-MANAGER';
    const VM_10619_USER = 'VM-10619-USER';
    const DVLA_IMPORT = 'DVLA-IMPORT';

    /**
     * @return array of values for the type BusinessRoleName
     */
    public static function getAll()
    {
        return [
            self::USER,
            self::VEHICLE_EXAMINER,
            self::DVSA_SCHEME_MANAGEMENT,
            self::DVSA_SCHEME_USER,
            self::DVSA_AREA_OFFICE_1,
            self::FINANCE,
            self::CUSTOMER_SERVICE_MANAGER,
            self::CUSTOMER_SERVICE_CENTRE_OPERATIVE,
            self::CRON,
            self::DVLA_OPERATIVE,
            self::DVSA_AREA_OFFICE_2,
            self::GVTS_TESTER,
            self::VM_10519_USER,
            self::DVLA_MANAGER,
            self::VM_10619_USER,
            self::DVLA_IMPORT,
        ];
    }

    /**
     * @param mixed $key a candidate BusinessRoleName value
     *
     * @return true if $key is in the list of known values for the type
     */
    public static function exists($key)
    {
        return in_array($key, self::getAll(), true);
    }
}
