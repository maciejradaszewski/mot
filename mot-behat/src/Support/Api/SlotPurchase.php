<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\Response;

class SlotPurchase extends MotApi
{
    const END_POINT_SLOT_PAYMENT       = 'slots/add-instant-settlement';
    const END_POINT_REFUND             = 'slots/refund/%s';
    const END_POINT_TRANSACTION_SEARCH = 'slots/transaction/search/%s';

    /**
     * @param string $token
     * @param array  $body
     *
     * @return Response
     */
    public function makePaymentForSlot($token, $body = [])
    {
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
}
