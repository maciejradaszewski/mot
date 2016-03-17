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
    const SLOT_USAGE = '/slots/report/slot-usage';
    const SLOT_USAGE_NUMBER = '/slots/report/slot-usage-number/site/%d';

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

    public function getSlotUsage($token, $organisationId, \DateTime $fromDate = null, \DateTime $toDate = null)
    {
        $data = ["limit" => 10, "organisation" => $organisationId];

        if ($fromDate !== null) {
            $data["dateFrom"] = $fromDate->format("Y-m-d");
        }

        if ($toDate !== null) {
            $data["dateTo"] = $toDate->format("Y-m-d");
        }

        $path = self::SLOT_USAGE . "?" . http_build_query($data);

        return $this->sendRequest($token, 'GET', $path);
    }

    public function getSLotUsageNumber($token, $siteId,$organisationId, \DateTime $fromDate = null, \DateTime $toDate = null)
    {
        $data = ["organisation" => $organisationId];

        if ($fromDate !== null) {
            $data["dateFrom"] = $fromDate->format("Y-m-d");
        }

        if ($toDate !== null) {
            $data["dateTo"] = $toDate->format("Y-m-d");
        }

        $path = sprintf(self::SLOT_USAGE_NUMBER, $siteId) . "?" . http_build_query($data);

        return $this->sendRequest($token, 'GET', $path);
    }
}
