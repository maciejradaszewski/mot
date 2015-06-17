<?php

namespace DvsaCommon\Utility;

use DvsaClient\Entity\Address as AddressCli;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaEntities\Entity\Address as AddressEntity;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Class Address
 * A set of static methods to make working with Address more convenient
 *
 * @package DvsaCommon\Utility
 */
class AddressUtils
{
    /**
     * Turn an address array or Address object into a string.
     *
     * @param array|AddressEntity $address address data
     * @param string              $sprtr   address parts separator
     *
     * @return string
     */
    public static function stringify($address, $sprtr = ', ')
    {
        if (is_array($address)) {
            return self::stringifyArr($address, $sprtr);
        } elseif (is_object($address)) {
            return self::stringifyEntity($address, $sprtr);
        }

        return null;
    }

    /**
     * @param array $address
     *
     * @return string
     */
    private static function stringifyArr(array $address, $sprtr = null)
    {
        if (empty($address)) {
            return '';
        }

        // Force the array to come out in the correct order..
        $default = [
            'addressLine1' => null,
            'addressLine2' => null,
            'addressLine3' => null,
            'addressLine4' => null,
            'town'         => null,
            'postcode'     => null,
        ];

        return join($sprtr, array_filter(array_intersect_key(array_replace($default, $address), $default)));
    }

    /**
     * @param AddressEntity $address
     *
     * @return string
     */
    private static function stringifyEntity($addressObj, $sprtr = null)
    {
        if (!($addressObj instanceof AddressEntity)
            && !($addressObj instanceof AddressCli)
            && !($addressObj instanceof AddressDto)
        ) {
            return null;
        }

        $hydrator = new Hydrator();
        $address = $hydrator->extract($addressObj);

        return self::stringifyArr($address, $sprtr);
    }
}
