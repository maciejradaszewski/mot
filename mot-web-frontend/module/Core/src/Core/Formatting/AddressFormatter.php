<?php

namespace Core\Formatting;

use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Formatting\Utf8Escaper;
use DvsaCommon\Utility\ArrayUtils;

class AddressFormatter
{
    protected static $addressPartsGlue = ',</br>';

    public static function escapedDtoToMultiLine(AddressDto $dto = null)
    {
       return static::escapeAddressToMultiLine(
           $dto->getAddressLine1(),
           $dto->getAddressLine2(),
           $dto->getAddressLine3(),
           $dto->getAddressLine4(),
           $dto->getTown(),
           $dto->getPostcode()
       );
    }

    public static function escapeAddressToMultiLine(
        $addressLine1 = null,
        $addressLine2 = null,
        $addressLine3 = null,
        $addressLine4 = null,
        $town = null,
        $postcode = null
    )
    {
        $escaper = new Utf8Escaper();

        $lines = [
            $escaper->escapeHtml($addressLine1),
            $escaper->escapeHtml($addressLine2),
            $escaper->escapeHtml($addressLine3),
            $escaper->escapeHtml($addressLine4),
            $escaper->escapeHtml($town),
            $escaper->escapeHtml($postcode),
        ];

        return ArrayUtils::joinNonEmpty(static::$addressPartsGlue, $lines);
    }
}
