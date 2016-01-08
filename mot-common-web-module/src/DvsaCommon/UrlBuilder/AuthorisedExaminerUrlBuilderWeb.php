<?php

namespace DvsaCommon\UrlBuilder;

/**
 * Url Builder for web for Authorised Examiner.
 *
 * @deprecated Use the route name directly instead, while using the URL generator helper.
 */
class AuthorisedExaminerUrlBuilderWeb extends AbstractUrlBuilder
{
    const MAIN                                  = '/authorised-examiner[/:id]';
    const SEARCH                                = '/search';
    const CREATE                                = '/create';
    const CREATE_AE_CONFIRMATION                = '/confirmation';
    const EDIT                                  = '/edit';
    const EDIT_STATUS                           = '/edit-status';
    const EDIT_STATUS_CONFIRM                   = '/confirmation';

    const SLOTS                                 = '/slots';
    const SLOTS_SETTINGS                        = '/settings';
    const SLOTS_PURCHASE                        = '/purchase';
    const SLOTS_PURCHASE_DETAILS                = '/details';
    const SLOTS_PURCHASE_PAYMENT_COMPLETE       = '/payment-complete';
    const SLOTS_PURCHASE_DIRECT_DEBIT           = '/direct-debit';
    const SLOTS_PURCHASE_DIRECT_DEBIT_CANCEL    = '/cancel/:directDebitId';
    const SLOTS_PURCHASE_DIRECT_DEBIT_AMEND     = '/amend/:directDebitId';
    const ADD_SLOTS                             = '/add-slots';
    const ADD_SLOTS_CONFIRMATION                = '/add-slots-confirmation';
    const SITE_SLOT_USAGE                       = '/site/:id/slots-usage[/page/:page]';

    const LIST_USER_ROLES                       = '/:personId/list-roles';
    const ROLES                                 = '/roles';
    const CONFIRM_NOMINATION                    = '/:nomineeId/confirm-nomination/:roleId';
    const REMOVE_ROLE                           = '/remove-role/:roleId';
    const REMOVE_ROLE_CONFIRMATION              = '/:personId/remove-role-confirmation';
    const PRINCIPALS                            = '/principals';
    const REMOVE_PRINCIPAL_CONFIRMATION         = '/:principalId/remove-principal-confirmation';

    const VIEW_TRANSACTION                      = '/transactions/:transaction[:extension]';
    const TRANSACTIONS                          = '/transactions[/page/:page][:extension]';

    const MOT_TEST_LOG                          = '/mot-test-log';
    const MOT_TEST_LOG_CSV                      = '/csv';

    const SITE = '/site';
    const SITE_LINK = '/link';
    const SITE_UNLINK = '/unlink/:linkId';

    protected $routesStructure
        = [
            self::MAIN =>
                [
                    self::SEARCH                        => '',
                    self::CREATE                        => [
                        self::CREATE_AE_CONFIRMATION => '',
                    ],
                    self::EDIT           => '',
                    self::EDIT_STATUS    => [
                        self::EDIT_STATUS_CONFIRM => '',
                    ],
                    self::SLOTS          => [
                        self::SLOTS_SETTINGS => '',
                        self::SLOTS_PURCHASE => [
                            self::SLOTS_PURCHASE_DETAILS          => '',
                            self::SLOTS_PURCHASE_PAYMENT_COMPLETE => '',
                            self::SLOTS_PURCHASE_DIRECT_DEBIT     => [
                                self::SLOTS_PURCHASE_DIRECT_DEBIT_CANCEL => '',
                                self::SLOTS_PURCHASE_DIRECT_DEBIT_AMEND  => '',
                            ],
                        ],
                    ],
                    self::VIEW_TRANSACTION => '',
                    self::TRANSACTIONS => '',
                    self::SITE_SLOT_USAGE => '',
                    self::ADD_SLOTS => '',
                    self::ADD_SLOTS_CONFIRMATION => '',
                    self::ROLES => '',
                    self::LIST_USER_ROLES => '',
                    self::CONFIRM_NOMINATION => '',
                    self::REMOVE_ROLE => '',
                    self::REMOVE_ROLE_CONFIRMATION => '',
                    self::PRINCIPALS => '',
                    self::REMOVE_PRINCIPAL_CONFIRMATION => '',
                    self::MOT_TEST_LOG             => [
                        self::MOT_TEST_LOG_CSV => '',
                    ],
                    self::SITE                          => [
                        self::SITE_LINK   => '',
                        self::SITE_UNLINK => '',
                    ],
                ],
        ];

    public function __construct($id = null)
    {
        $this->appendRoutesAndParams(self::MAIN);

        if ($id !== null) {
            $this->routeParam('id', $id);
        }

        return $this;
    }

    public static function of($id = null)
    {
        return new static($id);
    }

    public static function create()
    {
        return self::of()->appendRoutesAndParams(self::CREATE);
    }

    public static function createConfirm()
    {
        return self::create()
            ->appendRoutesAndParams(self::CREATE_AE_CONFIRMATION);
    }

    public function aeSearch()
    {
        return $this->appendRoutesAndParams(self::SEARCH);
    }

    public static function aeEdit($id)
    {
        return self::of($id)->appendRoutesAndParams(self::EDIT);
    }

    public static function aeEditStatus($id)
    {
        return self::of($id)->appendRoutesAndParams(self::EDIT_STATUS);
    }

    public static function aeEditStatusConfirm($id)
    {
        return self::aeEditStatus($id)->appendRoutesAndParams(self::EDIT_STATUS_CONFIRM);
    }

    public static function roles($aeId)
    {
        return self::of($aeId)->appendRoutesAndParams(self::ROLES);
    }

    public static function principals($id)
    {
        return self::of($id)->appendRoutesAndParams(self::PRINCIPALS);
    }

    public static function principalRemove($aeId, $principalId)
    {
        return self::of($aeId)
            ->appendRoutesAndParams(self::REMOVE_PRINCIPAL_CONFIRMATION)
            ->routeParam('principalId', $principalId);
    }

    /**
     * @param int $orgId
     *
     * @return $this
     */
    public static function motTestLog($orgId)
    {
        return (new static($orgId))->appendRoutesAndParams(self::MOT_TEST_LOG);
    }

    /**
     * @param int $orgId
     *
     * @return $this
     */
    public static function motTestLogDownloadCsv($orgId)
    {
        return self::motTestLog($orgId)->appendRoutesAndParams(self::MOT_TEST_LOG_CSV);
    }

    public static function slots($aeId)
    {
        return self::of($aeId)->appendRoutesAndParams(self::SLOTS);
    }

    private static function site($aeId)
    {
        return self::of($aeId)
            ->appendRoutesAndParams(self::SITE);
    }

    public static function siteLink($aeId)
    {
        return self::site($aeId)->appendRoutesAndParams(self::SITE_LINK);
    }

    public static function siteUnlink($aeId, $linkId)
    {
        return self::site($aeId, $linkId)
            ->appendRoutesAndParams(self::SITE_UNLINK)
            ->routeParam('linkId', $linkId);
    }
}
