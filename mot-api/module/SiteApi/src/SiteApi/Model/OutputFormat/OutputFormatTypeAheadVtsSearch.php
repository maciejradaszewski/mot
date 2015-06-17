<?php

namespace SiteApi\Model\OutputFormat;

use DvsaCommonApi\Model\OutputFormat;
use DvsaCommon\Utility\AddressUtils;

/**
 * Class OutputFormatTypeAheadVtsSearch
 *
 * @package SiteApi\Model\OutputFormat
 */
class OutputFormatTypeAheadVtsSearch extends OutputFormat
{
    /**
     * Responsible for extracting the current item into the required format
     *
     * @param $results
     * @param $key
     * @param $item \DvsaEntities\Entity\VehicleTestingStationSearch
     *
     * @return array|mixed
     */
    public function extractItem(&$results, $key, $item)
    {
        $key = 123; // phpmd fudge
        $siteNumber = $item['_source']['siteNumber'];

        if ($siteNumber) {
            $results[$siteNumber] = $this->formatReturn($item['_source']);
        }
    }

    protected function formatReturn($item)
    {
        $address = [
            'addressLine1' => $item['addressLine1'],
            'addressLine2' => $item['addressLine2'],
            'addressLine3' => $item['addressLine3'],
            'addressLine4' => $item['addressLine4'],
            'town' => $item['town'],
            'postcode' => $item['postcode'],
        ];

        return
            $item['siteNumber'] . ', ' .
            $item['name'] . ', ' .
            AddressUtils::stringify($address);
    }
}
