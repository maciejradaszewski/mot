<?php

namespace MotFitnesse\Util;

/**
 * Url Builder for api for the Account pages
 */
class AccountUrlBuilder extends AbstractUrlBuilder
{
    const RESET_PASSWORD = '/reset-password';
    const RESET_PASSWORD_TOKEN = '/:token';
    const VALIDATE_USERNAME = '/validate-username';

    protected $routesStructure
        = [
            self::RESET_PASSWORD => [
                self::RESET_PASSWORD_TOKEN => '',
                self::VALIDATE_USERNAME => '',
            ],
        ];

    public function __construct()
    {
        return $this;
    }

    public static function of()
    {
        return new static();
    }

    public static function resetPassword($token = null)
    {
        $url = self::of()
            ->appendRoutesAndParams(self::RESET_PASSWORD);

        if ($token !== null) {
            $url->appendRoutesAndParams(self::RESET_PASSWORD_TOKEN);
            $url->routeParam('token', $token);
        }

        return $url;
    }

    public static function validateUsername()
    {
        return self::resetPassword()
            ->appendRoutesAndParams(self::VALIDATE_USERNAME);
    }
}
