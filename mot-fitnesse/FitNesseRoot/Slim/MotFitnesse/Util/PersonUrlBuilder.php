<?php

namespace MotFitnesse\Util;

/**
 * Url builder for 'person' resources
 */
class PersonUrlBuilder extends AbstractUrlBuilder
{
    const PERSON = '/person';
    const BY_ID = '/:id';
    const BY_IDENTIFIER = '/username/:login';
    const AUTHORISED_EXAMINER = '/authorised-examiner';
    const RBAC_ROLES = "/rbac-roles";
    const HELP_DESK_PROFILE = '/help-desk-profile-unrestricted';
    const HELP_DESK_PROFILE_RESTRICTED = '/help-desk-profile-restricted';
    const PERSON_SEARCH = 'search-person';
    const MOT_TESTING = '/mot-testing';
    const RESET_PIN = '/reset-pin';
    const PASSWORD_EXPIRY = 'password-expiry-notification';
    const RESET_CLAIM_ACCOUNT = '/reset-claim-account';

    protected $routesStructure
        = [
            self::PERSON        => [
                self::BY_ID         => [
                    self::AUTHORISED_EXAMINER          => '',
                    self::RBAC_ROLES                   => '',
                    self::HELP_DESK_PROFILE            => '',
                    self::HELP_DESK_PROFILE_RESTRICTED => '',
                    self::MOT_TESTING                  => '',
                    self::RESET_PIN                    => '',
                    self::RESET_CLAIM_ACCOUNT            => '',
                ],
                self::BY_IDENTIFIER => '',
            ],
            self::PERSON_SEARCH => '',
            self::PASSWORD_EXPIRY => '',
        ];

    public static function byId($id)
    {
        $urlBuilder = new self();

        return $urlBuilder
            ->appendRoutesAndParams(self::PERSON)
            ->appendRoutesAndParams(self::BY_ID)
            ->routeParam('id', $id);
    }

    public static function byIdentifier($login)
    {
        $urlBuilder = new self();

        return $urlBuilder
            ->appendRoutesAndParams(self::PERSON)
            ->appendRoutesAndParams(self::BY_IDENTIFIER)
            ->routeParam('login', $login);
    }

    public function authorisedExaminer()
    {
        return $this->appendRoutesAndParams(self::AUTHORISED_EXAMINER);
    }

    public function rbacRoles()
    {
        return $this->appendRoutesAndParams(self::RBAC_ROLES);
    }

    public static function personSearch()
    {
        $urlBuilder = new self();

        return $urlBuilder
            ->appendRoutesAndParams(self::PERSON_SEARCH);
    }

    public function helpDeskProfile()
    {
        return $this->appendRoutesAndParams(self::HELP_DESK_PROFILE);
    }

    public function helpDeskProfileRestricted()
    {
        return $this->appendRoutesAndParams(self::HELP_DESK_PROFILE_RESTRICTED);
    }

    public static function motTesting($id)
    {
        return self::byId($id)->appendRoutesAndParams(self::MOT_TESTING);
    }

    public static function resetPin($id)
    {
        return self::byId($id)->appendRoutesAndParams(self::RESET_PIN);
    }

    public static function passwordExpiry()
    {
        $urlBuilder = new self();

        return $urlBuilder
            ->appendRoutesAndParams(self::PASSWORD_EXPIRY);
    }

    public static function resetClaimAccount($id)
    {
        return self::byId($id)->appendRoutesAndParams(self::RESET_CLAIM_ACCOUNT);
    }
}
