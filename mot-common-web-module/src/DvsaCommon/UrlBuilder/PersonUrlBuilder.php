<?php

namespace DvsaCommon\UrlBuilder;

/**
 * Url builder for `person` resource
 * Paths:
 *  person/:id
 *  person/:login
 */
class PersonUrlBuilder extends AbstractUrlBuilder
{
    const PERSON = 'person';
    const BY_ID = '/:id';
    const BY_IDENTIFIER = '/username/:login';
    const AUTHORISED_EXAMINER = '/authorised-examiner';
    const RBAC_ROLES = "/rbac-roles";
    const HELP_DESK_PROFILE = '/help-desk-profile-restricted';
    const HELP_DESK_PROFILE_UNRESTRICTED = '/help-desk-profile-unrestricted';
    const PERSON_SEARCH = 'search-person';
    const MOT_TESTING = '/mot-testing';
    const RESET_PIN = '/reset-pin';
    const RESET_CLAIM_ACCOUNT = '/reset-claim-account';
    const MANAGE_INTERNAL_ROLES = '/roles';

    protected $routesStructure
        = [
            self::PERSON        => [
                self::BY_ID         => [
                    self::AUTHORISED_EXAMINER            => '',
                    self::RBAC_ROLES                     => '',
                    self::HELP_DESK_PROFILE              => '',
                    self::HELP_DESK_PROFILE_UNRESTRICTED => '',
                    self::MOT_TESTING                    => '',
                    self::RESET_PIN                      => '',
                    self::RESET_CLAIM_ACCOUNT            => '',
                    self::MANAGE_INTERNAL_ROLES          => '',
                ],
                self::BY_IDENTIFIER => '',
            ],
            self::PERSON_SEARCH => '',
        ];

    /**
     * Return the url to get a person from the api by his Id
     *
     * @param $id
     * @return $this
     */
    public static function byId($id)
    {
        $urlBuilder = new self();

        return $urlBuilder
            ->appendRoutesAndParams(self::PERSON)
            ->appendRoutesAndParams(self::BY_ID)
            ->routeParam('id', $id);
    }

    /**
     * Return the url to get a person from the api by his login
     *
     * @param $login
     * @return $this
     */
    public static function byIdentifier($login)
    {
        $urlBuilder = new self();

        return $urlBuilder
            ->appendRoutesAndParams(self::PERSON)
            ->appendRoutesAndParams(self::BY_IDENTIFIER)
            ->routeParam('login', $login);
    }

    /**
     * Return the url to get an authorised examiner from the api
     *
     * @return $this
     */
    public function authorisedExaminer()
    {
        return $this->appendRoutesAndParams(self::AUTHORISED_EXAMINER);
    }

    /**
     * @return $this
     */
    public function rbacRoles()
    {
        return $this->appendRoutesAndParams(self::RBAC_ROLES);
    }

    /**
     * Return the url to search for a person
     *
     * @return $this
     */
    public static function personSearch()
    {
        $urlBuilder = new self();

        return $urlBuilder
            ->appendRoutesAndParams(self::PERSON_SEARCH);
    }

    /**
     * Return the url to get the person profile for the helpdesk
     *
     * @param $personId
     * @return $this
     */
    public static function helpDeskProfile($personId)
    {
        return self::byId($personId)->appendRoutesAndParams(self::HELP_DESK_PROFILE);
    }

    /**
     * Return the url to get the person unrestricted profile for the helpdesk
     *
     * @param $personId
     * @return $this
     */
    public static function helpDeskProfileUnrestricted($personId)
    {
        return self::byId($personId)->appendRoutesAndParams(self::HELP_DESK_PROFILE_UNRESTRICTED);
    }

    /**
     * Return the url to get the person mot test list
     *
     * @param int $personId
     * @return $this
     */
    public static function motTesting($personId)
    {
        return self::byId($personId)->appendRoutesAndParams(self::MOT_TESTING);
    }

    /**
     * Return the url to reset the pin of a person
     *
     * @param $id
     * @return $this
     */
    public static function resetPin($id)
    {
        return self::byId($id)->appendRoutesAndParams(self::RESET_PIN);
    }

    /**
     * Return the url to reset the account of a person
     *
     * @param $personId
     * @return $this
     */
    public static function resetClaimAccount($personId)
    {
        return self::byId($personId)->appendRoutesAndParams(self::RESET_CLAIM_ACCOUNT);
    }

    /**
     * Return the url to manage the internal roles for the user
     * @param $personId
     * @return $this
     */
    public static function manageInternalRoles($personId)
    {
        return self::byId($personId)->appendRoutesAndParams(self::MANAGE_INTERNAL_ROLES);
    }
}
