<?php

namespace Dvsa\Mot\Behat\Support\Api;

use DateTime;
use Dvsa\Mot\Behat\Support\Response;

class SlotPurchase extends MotApi
{
    const END_POINT_SLOT_PAYMENT           = 'slots/add-instant-settlement';
    const END_POINT_REFUND                 = 'slots/refund/%s';
    const END_POINT_TRANSACTION_SEARCH     = 'slots/transaction/search/%s';
    const END_POINT_ADJUSTMENT             = 'slots/amendment/%s/adjustment';
    const END_POINT_ADJUSTMENT_REASON_TYPE = 'slots/amendment-reason/type/%s';
    const END_POINT_REVERSAL               = 'slots/amendment/%s/charge-back';
    const END_POINT_REDIRECTION_DATE       = 'slots/redirection-data';

    /**
     * Adjust transaction
     *
     * @param       $transactionId
     * @param       $token
     * @param array $body
     *
     * @return Response
     */
    public function adjustTransaction($token, $transactionId, $body = [])
    {
        $url = sprintf(self::END_POINT_ADJUSTMENT, $transactionId);

        return $this->sendRequest($token, 'POST', $url, $body);
    }

    /**
     * @param       $token
     * @param       $slots
     * @param       $organisation
     * @param float $price
     *
     * @return Response
     */
    public function makePaymentForSlot($token, $slots, $organisation, $price = 2.05)
    {
        $amount = number_format($price * $slots, 2, '.', '');
        $body   = [
            'organisation' => $organisation,
            'slots'        => $slots,
            'amount'       => $amount,
            'paidAmount'   => $amount,
            'paymentType'  => 'cheque',
            'autoRefund'   => false,
            'paymentData'  => [
                'accountName'  => 'Tester',
                'chequeNumber' => rand(10000, 99999),
                'slipNumber'   => rand(20000, 99999),
                'chequeDate'   => (new DateTime('-10 days'))->format('Y-m-d'),
            ]

        ];

        return $this->sendRequest($token, 'POST', self::END_POINT_SLOT_PAYMENT, $body);
    }

    /**
     * @param string $token
     * @param int    $organisation
     * @param [] $body
     *
     * @return Response
     */
    public function requestRefund($token, $organisation, $body)
    {
        $url = sprintf(self::END_POINT_REFUND, $organisation);

        return $this->sendRequest($token, 'POST', $url, $body);
    }

    /**
     * @param string $token
     * @param int    $organisation
     * @param [] $param
     *
     * @return Response
     */
    public function requestRefundSummaryDetails($token, $organisation, $param)
    {
        $url = sprintf(self::END_POINT_REFUND, $organisation) . '?' . http_build_query($param);

        return $this->sendRequest($token, 'GET', $url);
    }

    /**
     * @param $token
     * @param $invoiceNumber
     *
     * @return Response
     */
    public function searchByInvoiceNumber($token, $invoiceNumber)
    {
        $param = [
            'type' => 2
        ];
        $url   = sprintf(self::END_POINT_TRANSACTION_SEARCH, $invoiceNumber) . '?' . http_build_query($param);

        return $this->sendRequest($token, 'GET', $url);
    }

    /**
     * @param $token
     * @param $reference
     *
     * @return Response
     */
    public function searchByPaymentReference($token, $reference)
    {
        $param = [
            'type' => 1
        ];
        $url   = sprintf(self::END_POINT_TRANSACTION_SEARCH, $reference) . '?' . http_build_query($param);

        return $this->sendRequest($token, 'GET', $url);
    }

    /**
     * @param $token
     * @param $type
     *
     * @return Response
     */
    public function getAmendmentReasonsByType($token, $type)
    {
        $url = sprintf(self::END_POINT_ADJUSTMENT_REASON_TYPE, $type);

        return $this->sendRequest($token, 'GET', $url);
    }

    /**
     * @param     $token
     * @param int $transactionId
     *
     * @return Response
     */
    public function reverseTransaction($token, $transactionId)
    {
        $url = sprintf(self::END_POINT_REVERSAL, $transactionId);

        return $this->sendRequest($token, 'POST', $url);
    }

    /**
     * @param $token
     * @param $slots
     * @param $price
     * @param $invoiceNumber
     *
     * @return Response
     */
    public function getRedirectionData($token, $slots, $price, $invoiceNumber)
    {
        $amount = number_format($price * $slots, 2, '.', '');
        $body   = [
            'scope'   => 'CARD',
            'payload' => [
                'customer_reference' => 'AE555',
                'total_amount'       => $amount,
                'scope'              => 'CARD',
                'user_id'            => 'test',
                'customer_name'      => 'MOT User',
                'redirect_uri'       => 'http://mot-web-frontend.mot.gov.uk',
                'cost_centre'        => '12345,90987',
                'payment_data'       => [
                    [
                        'sales_reference'   => $invoiceNumber,
                        'amount'            => $amount,
                        'product_reference' => 'MOT_SLOTS',
                    ]
                ]
            ],
        ];

        return $this->sendRequest($token, 'POST', self::END_POINT_REDIRECTION_DATE, $body);
    }
}
