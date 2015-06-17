<?php

namespace SiteApi\Model\OutputFormat;

use DvsaCommonApi\Model\OutputFormat;
use DvsaCommon\Utility\AddressUtils;

/**
 * Class OutputFormatDataTablesVtsSearch
 *
 * @package DvsaMotApi\Model
 */
class OutputFormatDataTablesVtsSearch extends OutputFormat
{
    /**
     * Responsible for extracting the current item into the required format
     *
     * @param $results
     * @param $key
     * @param $item
     *
     * @return array|mixed
     */
    public function extractItem(&$results, $key, $item)
    {
        $key = 123; // phpmd fudge
        $siteNumber = $item['_source']['siteNumber'];

        $result = [];
        $result['siteNumber']  = $siteNumber;
        $result['name']        = $item['_source']['name'];
        $result['address']     = $this->getInlineAddress($item['_source']);
        $result['town']        = $item['_source']['town'];
        $result['postcode']    = $item['_source']['postcode'];
        $result['telephone']   = null;
        $result['roles']       = count($item['_source']['classes']) ?
            join(', ', $item['_source']['classes']) : 'Undefined';
        $result['type']        = $item['_source']['type'];
        $result['status']      = $item['_source']['status'];

        $results[$siteNumber] = $result;
    }

    protected function getInlineAddress($item)
    {
        $address = [
            'addressLine1' => $item['addressLine1'],
            'addressLine2' => $item['addressLine2'],
            'addressLine3' => $item['addressLine3'],
            'addressLine4' => $item['addressLine4'],
        ];

        return AddressUtils::stringify($address);
    }
}
