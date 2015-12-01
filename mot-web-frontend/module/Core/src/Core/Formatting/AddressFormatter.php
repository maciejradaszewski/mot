<?php

namespace Core\Formatting;

use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Formatting\Utf8Escaper;
use DvsaCommon\Utility\ArrayUtils;

class AddressFormatter
{
    public static function escapedDtoToMultiLine(AddressDto $dto = null)
    {
        $escaper = new Utf8Escaper();

        $lines = [
            $escaper->escapeHtml($dto->getAddressLine1()),
            $escaper->escapeHtml($dto->getAddressLine2()),
            $escaper->escapeHtml($dto->getAddressLine3()),
            $escaper->escapeHtml($dto->getAddressLine4()),
            $escaper->escapeHtml($dto->getTown()),
            $escaper->escapeHtml($dto->getPostcode()),
        ];

        return ArrayUtils::joinNonEmpty(',</br>', $lines);
    }
}
