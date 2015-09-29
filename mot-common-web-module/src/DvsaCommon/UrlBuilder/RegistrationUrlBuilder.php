<?php

namespace DvsaCommon\UrlBuilder;

class RegistrationUrlBuilder extends AbstractUrlBuilder
{
    const MAIN = 'account/register';
    const CHECK_EMAIL = '/check-email';

    /**
     * Keys define the route, we need to set the value of each because of the way AbstractUrlBuilder checks
     * the routing when converting back to a string
     * @see AbstractUrlBuilder::verifyOrderOfRoute
     * @var array
     */
    protected $routesStructure = [
        self::MAIN => [
            self::CHECK_EMAIL => '',
        ]
    ];

    /**
     * @return $this
     */
    public static function register()
    {
        return self::of()->appendRoutesAndParams(self::MAIN);
    }

    /**
     * @return $this
     */
    public static function checkEmail()
    {
        return self::register()->appendRoutesAndParams(self::CHECK_EMAIL);
    }
}
