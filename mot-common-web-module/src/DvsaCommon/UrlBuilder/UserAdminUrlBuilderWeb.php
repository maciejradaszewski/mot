<?php

namespace DvsaCommon\UrlBuilder;

/**
 * Url Builder for web for the User Admin pages
 */
class UserAdminUrlBuilderWeb extends AbstractUrlBuilder
{
    const MAIN              = '/user-admin';
    const USER_SEARCH       = '/search';
    const EMAIL_CHANGE      = '/email';
    const USER_RESULTS      = '/results';
    const USER_PROFILE      = '/user-profile/:personId';
    const SECURITY_QUESTION = '/security-question/:questionId';
    const CLAIM_ACCOUNT     = '/claim-reset';
    const CLAIM_ACCOUNT_POST = '/claim-reset/post';

    const PASSWORD_RESET = '/password-reset';
    const PASSWORD_RESET_OK = '/password-reset/ok';
    const PASSWORD_RESET_NOT_OK = '/password-reset/nok';

    const USERNAME_RECOVER = '/username-recover';
    const USERNAME_RECOVER_OK = '/username-recover/ok';


    protected $routesStructure
        = [
            self::MAIN =>
                [
                    self::USER_SEARCH       => '',
                    self::USER_RESULTS      => '',
                    self::USER_PROFILE      => [
                        self::SECURITY_QUESTION => '',
                        self::CLAIM_ACCOUNT => '',
                        self::CLAIM_ACCOUNT_POST => '',
                        self::PASSWORD_RESET => '',
                        self::PASSWORD_RESET_OK => '',
                        self::PASSWORD_RESET_NOT_OK => '',
                        self::USERNAME_RECOVER => '',
                        self::USERNAME_RECOVER_OK => '',
                        self::EMAIL_CHANGE => '',
                    ],
                ],
        ];

    public function __construct()
    {
        $this->appendRoutesAndParams(self::MAIN);
        return $this;
    }

    public static function of()
    {
        return new static();
    }

    public function userSearch()
    {
        $this->appendRoutesAndParams(self::USER_SEARCH);
        return $this;
    }

    public static function emailChange($personId)
    {
        return self::userProfile($personId)
            ->appendRoutesAndParams(self::EMAIL_CHANGE);
    }

    public function userResults()
    {
        $this->appendRoutesAndParams(self::USER_RESULTS);
        return $this;
    }

    public static function userProfile($personId)
    {
        return self::of()->appendRoutesAndParams(self::USER_PROFILE)
            ->routeParam('personId', $personId);
    }

    public static function userProfileSecurityQuestion($personId, $questionId)
    {
        return self::userProfile($personId)
            ->appendRoutesAndParams(self::SECURITY_QUESTION)
            ->routeParam('questionId', $questionId);
    }

    public static function userProfileClaimAccount($personId)
    {
        return self::of()->userProfile($personId)
            ->appendRoutesAndParams(self::CLAIM_ACCOUNT);
    }

    public static function userProfileClaimAccountPost($personId)
    {
        return self::of()->userProfile($personId)
            ->appendRoutesAndParams(self::CLAIM_ACCOUNT_POST);
    }

    public static function userProfileResetPassword($personId)
    {
        return self::userProfile($personId)
            ->appendRoutesAndParams(self::PASSWORD_RESET);
    }

    public static function userProfileRecoverUsername($personId)
    {
        return self::userProfile($personId)
            ->appendRoutesAndParams(self::USERNAME_RECOVER);
    }
}
