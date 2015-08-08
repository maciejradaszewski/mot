<?php

namespace Dvsa\Mot\Behat\Support\Api;

use DateTime;
use Zend\Filter\Word\CamelCaseToSeparator;

/**
 * Class SlotReport
 *
 * @package Dvsa\Mot\Behat\Support\Api
 */
class SlotReport extends MotApi
{
    const CREATE_REPORT = '/slots/financial-report';
    const REPORT_STATUS = '/slots/financial-report/%s';

    private $reportCodes = [
        'slotBalance'      => 'MOT00001',
        'bankingBreakDown' => '91C376BF',
        'allPayments'      => '82FA1F0C',
        'generalLedger'    => 'D9FE6D0F',
    ];

    public function createReport($token, $reportType, $fromDate, $toDate)
    {
        $fromDate = (new DateTime($fromDate))->format('Y-m-d');
        $toDate   = (new DateTime($toDate))->format('Y-m-d');

        $param = [
            'dateFrom' => $fromDate,
            'dateTo'   => $toDate,
            'code'     => $this->reportCodes[$reportType],
        ];

        return $this->sendRequest($token, 'POST', self::CREATE_REPORT, $param);
    }

    public function getReportStatus($token, $code)
    {
        $path = sprintf(self::REPORT_STATUS, $code);

        return $this->sendRequest($token, 'GET', $path);
    }
}
