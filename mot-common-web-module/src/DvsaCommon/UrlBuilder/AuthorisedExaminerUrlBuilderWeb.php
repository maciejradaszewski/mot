<?php

namespace DvsaCommon\UrlBuilder;

/**
 * Url Builder for web for Authorised Examiner
 */
class AuthorisedExaminerUrlBuilderWeb extends AbstractUrlBuilder
{
    const MAIN                                  = '/authorised-examiner[/:id]';
    const SEARCH                                = '/search';
    const CREATE                                = '/create';
    const EDIT                                  = '/edit';

    const SLOTS                                 = '/slots';
    const SLOTS_USAGE                           = '/usage[/page/:page][:extension]';
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

    const VEHICLE_TESTING_STATION               = '/vehicle-testing-station/:vehicleTestingStationId';

    const VIEW_TRANSACTION                      = '/transactions/:transaction[:extension]';
    const TRANSACTIONS                          = '/transactions[/page/:page][:extension]';

    const MOT_TEST_LOG                          = '/mot-test-log';
    const MOT_TEST_LOG_DOWNLOAD_CSV             = '/download/csv';

    protected $routesStructure
        = [
            self::MAIN =>
                [
                    self::SEARCH         => '',
                    self::CREATE         => '',
                    self::EDIT           => '',
                    self::SLOTS          => [
                        self::SLOTS_USAGE    => '',
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
                    self::VEHICLE_TESTING_STATION => '',
                    self::MOT_TEST_LOG             => [
                        self::MOT_TEST_LOG_DOWNLOAD_CSV => '',
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

    public function aeCreate()
    {
        return $this->appendRoutesAndParams(self::CREATE);
    }

    public function aeSearch()
    {
        return $this->appendRoutesAndParams(self::SEARCH);
    }

    public function aeEdit()
    {
        return $this->appendRoutesAndParams(self::EDIT);
    }

    public static function roles($aeId)
    {
        return self::of($aeId)->appendRoutesAndParams(self::ROLES);
    }

    public function principals()
    {
        return $this->appendRoutesAndParams(self::PRINCIPALS);
    }

    public function principalRemove($principalId)
    {
        $this->appendRoutesAndParams(self::REMOVE_PRINCIPAL_CONFIRMATION);
        $this->routeParam('principalId', $principalId);

        return $this;
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
        return self::motTestLog($orgId)->appendRoutesAndParams(self::MOT_TEST_LOG_DOWNLOAD_CSV);
    }

    public static function slots($aeId)
    {
        return self::of($aeId)->appendRoutesAndParams(self::SLOTS);
    }

    public static function slotsUsage($aeId, $page = null, $extension = null)
    {
        $url = self::slots($aeId)
            ->appendRoutesAndParams(self::SLOTS_USAGE);

        if ($page > 0) {
            $url->routeParam('page', (int)$page);
        }

        if (!empty($extension)) {
            $url->routeParam('extension', $extension);
        }

        return $url;
    }

    public static function siteSlotUsage($aeId, $siteId, $pageNr = null)
    {
        $url = self::of($aeId)
            ->appendRoutesAndParams(self::SITE_SLOT_USAGE)
            ->routeParam('id', $siteId);

        if ($pageNr > 0) {
            $url->routeParam('page', $pageNr);
        }

        return $url;
    }
}
