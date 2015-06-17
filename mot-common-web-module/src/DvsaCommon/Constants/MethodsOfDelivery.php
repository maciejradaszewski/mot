<?php

namespace DvsaCommon\Constants;

class MethodsOfDelivery extends BaseEnumeration
{
    const EMAIL = 'EMAIL';
    const POST = 'POST';
    const POST_ADDRESS = 'VTS application, DVSA, Address line 2, Address Line 3, Postcode';
    const EMAIL_ADDRESS = 'applications@dvsa.gov.uk';

    public static function isEmailSelected($value)
    {
        return self::EMAIL === $value;
    }

    public static function isPostSelected($value)
    {
        return self::POST === $value;
    }
}
