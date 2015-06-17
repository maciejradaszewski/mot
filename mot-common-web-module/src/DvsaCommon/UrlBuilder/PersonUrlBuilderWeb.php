<?php

namespace DvsaCommon\UrlBuilder;

/**
 * Web urls builder for UserHome routes
 */
class PersonUrlBuilderWeb extends AbstractUrlBuilder
{
    const USER_HOME = '/';

    const STATS = '/stats';
    const MY_APPL = '/my-applications';

    const PROFILE = '/profile[/:id]';
    const EDIT = '/edit';
    const MOT_TESTING = '/mot-testing';
    const SECURITY_SETTINGS = '/security-settings';
    const SECURITY_QUESTIONS = '/security-question[/:questionNumber]';

    protected $routesStructure
        = [
            self::USER_HOME => '',
            self::STATS     => '',
            self::MY_APPL   => '',
            self::PROFILE   => [
                self::EDIT               => '',
                self::MOT_TESTING        => '',
                self::SECURITY_SETTINGS  => '',
                self::SECURITY_QUESTIONS => '',
            ],
        ];

    public static function home()
    {
        return self::of()->appendRoutesAndParams(self::USER_HOME);
    }

    public static function stats()
    {
        return self::of()->appendRoutesAndParams(self::STATS);
    }

    public static function myAppl()
    {
        return self::of()->appendRoutesAndParams(self::MY_APPL);
    }

    public static function profile($personId = null)
    {
        return self::of()->appendRoutesAndParams(self::PROFILE)
            ->routeParam('id', $personId);
    }

    public static function profileEdit()
    {
        return self::profile()->appendRoutesAndParams(self::EDIT);
    }

    public static function updateAuthMotTesting($personId = null)
    {
        return self::profile($personId)->appendRoutesAndParams(self::MOT_TESTING);
    }

    public static function securitySettings()
    {
        return self::profile()->appendRoutesAndParams(self::SECURITY_SETTINGS);
    }

    public static function securityQuestions($securityNumber = null)
    {
        $url = self::profile()->appendRoutesAndParams(self::SECURITY_QUESTIONS);
        if ($securityNumber !== null) {
            $url->routeParam('questionNumber', $securityNumber);
        }
        return $url;
    }
}
