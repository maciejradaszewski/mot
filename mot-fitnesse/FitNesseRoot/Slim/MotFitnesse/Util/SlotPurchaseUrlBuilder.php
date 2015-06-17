<?php

namespace MotFitnesse\Util;

/**
 * Class SlotPurchaseUrlBuilder
 *
 * Url Builder for the Events Api Call
 *
 * @package MotFitnesse\Util
 */
class SlotPurchaseUrlBuilder extends AbstractUrlBuilder
{
    const MAIN                       = '/slots';
    const BY_ID                      = '/:id';
    const ORG_ID                     = '/:org';
    const PURCHASE                   = '/purchase';
    const VALIDATE                   = '/validate';
    const TRX_INIT                   = '/transaction';
    const REDIRECTION                = '/redirection-data';
    const PAYMENT_DATA               = '/payment-data/:id';
    const TRX_COMPLETE               = '/transaction/complete/:org';
    const TRX_HISTORY                = '/report/transaction-history';
    const SUPPLIER                   = '/report/details/supplier';
    const PURCHASER                  = '/report/details/purchaser/:id';
    const ORDER_DETAIL               = '/report/details/order/:id';
    const SLOT_USAGE                 = '/report/slot-usage';
    const SLOT_USAGE_SITE            = '/report/slot-usage/site/:site';
    const SLOT_USAGE_NUMBER          = '/report/slot-usage-number';
    const SLOT_USAGE_NUMBER_SITE     = '/report/slot-usage-number/site/:site';
    const SETTLE_PAYMENT             = '/add-instant-settlement';
    const TRANSACTION_SEARCH         = '/transaction/search/:reference';
    const ADJUSTMENT                 = '/adjustment';
    const AMENDMENT_REASON           = '/amendment-reason';
    const DIRECT_DEBIT_OPTION        = '/direct-debit/:id/options';
    const DIRECT_DEBIT_MANDATE       = '/direct-debit/:id';
    const DIRECT_DEBIT_MANDATE_SETUP = '/direct-debit';
    const FINANCIAL_REPORT           = '/financial-report';
    const FINANCIAL_REPORT_GET       = '/financial-report/:id';
    const FINANCIAL_REPORT_DOWNLOAD  = '/financial-report/:id/download';

    protected $routesStructure
        = [
            self::MAIN =>
                [

                    self::VALIDATE                   => '',
                    self::TRX_COMPLETE               => '',
                    self::REDIRECTION                => '',
                    self::PAYMENT_DATA               => '',
                    self::TRX_INIT                   => '',
                    self::TRX_HISTORY                => '',
                    self::SUPPLIER                   => '',
                    self::PURCHASER                  => '',
                    self::ORDER_DETAIL               => '',
                    self::SLOT_USAGE_NUMBER          => '',
                    self::SLOT_USAGE_NUMBER_SITE     => '',
                    self::SLOT_USAGE                 => '',
                    self::SLOT_USAGE_SITE            => '',
                    self::SETTLE_PAYMENT             => '',
                    self::BY_ID                      => '',
                    self::TRANSACTION_SEARCH         => '',
                    self::ADJUSTMENT                 => '',
                    self::AMENDMENT_REASON           => '',
                    self::DIRECT_DEBIT_OPTION        => '',
                    self::DIRECT_DEBIT_MANDATE_SETUP => '',
                    self::DIRECT_DEBIT_MANDATE       => '',
                    self::FINANCIAL_REPORT           => '',
                    self::FINANCIAL_REPORT_GET       => '',
                    self::FINANCIAL_REPORT_DOWNLOAD  => '',
                    [
                        self::PURCHASE => '',
                    ],

                ],
        ];

    public function __construct()
    {
        $this->appendRoutesAndParams(self::MAIN);

        return $this;
    }

    /**
     * @return static
     */
    public static function of()
    {
        return new static();
    }

    /**
     * @return $this
     */
    public function listReport()
    {
        $this->appendRoutesAndParams(self::FINANCIAL_REPORT);

        return $this;
    }

    /**
     * @return $this
     */
    public function getReport($id)
    {
        $this->appendRoutesAndParams(self::FINANCIAL_REPORT_GET);
        $this->routeParam('id', $id);

        return $this;
    }

    /**
     * @return $this
     */
    public function createReport()
    {
        $this->appendRoutesAndParams(self::FINANCIAL_REPORT);

        return $this;
    }

    /**
     * @return $this
     */
    public function downloadReport($id)
    {
        $this->appendRoutesAndParams(self::FINANCIAL_REPORT_DOWNLOAD);
        $this->routeParam('id', $id);

        return $this;
    }

    /**
     * @return $this
     */
    public function validate()
    {
        $this->appendRoutesAndParams(self::VALIDATE);

        return $this;
    }

    /**
     * @return $this
     */
    public function reason()
    {
        $this->appendRoutesAndParams(self::AMENDMENT_REASON);

        return $this;
    }

    /**
     * @return $this
     */
    public function adjustment()
    {
        $this->appendRoutesAndParams(self::ADJUSTMENT);

        return $this;
    }

    public function slotConfiguration()
    {
        return $this->validate();
    }

    /**
     * @return $this
     */
    public function slotUsageNumber()
    {
        $this->appendRoutesAndParams(self::SLOT_USAGE_NUMBER);

        return $this;
    }

    /**
     * @param $site
     *
     * @return $this
     */
    public function siteSlotUsageNumber($site)
    {
        $this->appendRoutesAndParams(self::SLOT_USAGE_NUMBER_SITE);
        $this->routeParam('site', $site);

        return $this;
    }

    /**
     * @return $this
     */
    public function slotUsage()
    {
        $this->appendRoutesAndParams(self::SLOT_USAGE);

        return $this;
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function slotUsageSite($id)
    {
        $this->appendRoutesAndParams(self::SLOT_USAGE_SITE);
        $this->routeParam('site', $id);

        return $this;
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function purchasee($id)
    {
        $this->appendRoutesAndParams(self::PURCHASE);
        $this->routeParam('id', $id);

        return $this;
    }

    /**
     * @return $this
     */
    public function initTransaction()
    {
        $this->appendRoutesAndParams(self::TRX_INIT);

        return $this;
    }

    /**
     * @return $this
     */
    public function settlePayment()
    {
        $this->appendRoutesAndParams(self::SETTLE_PAYMENT);

        return $this;
    }

    /**
     * @return $this
     */
    public function setUpMandate()
    {
        $this->appendRoutesAndParams(self::DIRECT_DEBIT_MANDATE_SETUP);

        return $this;
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function getMandate($id)
    {
        $this->appendRoutesAndParams(self::DIRECT_DEBIT_MANDATE);
        $this->routeParam('id', $id);

        return $this;
    }

    /**
     * @return $this
     */
    public function transactionHistory()
    {
        $this->appendRoutesAndParams(self::TRX_HISTORY);

        return $this;
    }

    /**
     * @return $this
     */
    public function slotUsageReport()
    {
        $this->appendRoutesAndParams(self::SLOT_USAGE);

        return $this;
    }

    /**
     * @return $this
     */
    public function supplierDetails()
    {
        $this->appendRoutesAndParams(self::SUPPLIER);

        return $this;
    }

    /**
     * @return $this
     */
    public function redirection()
    {
        $this->appendRoutesAndParams(self::REDIRECTION);

        return $this;
    }

    /**
     * @return $this
     */
    public function directDebitOption($id)
    {
        $this->appendRoutesAndParams(self::DIRECT_DEBIT_OPTION);
        $this->routeParam('id', $id);

        return $this;
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function paymentData($id)
    {
        $this->appendRoutesAndParams(self::PAYMENT_DATA);
        $this->routeParam('id', $id);

        return $this;
    }

    /**
     * @param  int $transactionId
     *
     * @return $this
     */
    public function purchaserDetails($transactionId)
    {
        $this->appendRoutesAndParams(self::PURCHASER);
        $this->routeParam('id', $transactionId);

        return $this;
    }

    /**
     * @param $reference
     *
     * @return $this
     */
    public function transactionSearch($reference)
    {
        $this->appendRoutesAndParams(self::TRANSACTION_SEARCH);
        $this->routeParam('reference', $reference);

        return $this;
    }

    /**
     * @param  int $transactionId
     *
     * @return $this
     */
    public function orderDetails($transactionId)
    {
        $this->appendRoutesAndParams(self::ORDER_DETAIL);
        $this->routeParam('id', $transactionId);

        return $this;
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function completeTransaction($id)
    {
        $this->appendRoutesAndParams(self::TRX_COMPLETE);
        $this->routeParam('org', $id);

        return $this;
    }
}
