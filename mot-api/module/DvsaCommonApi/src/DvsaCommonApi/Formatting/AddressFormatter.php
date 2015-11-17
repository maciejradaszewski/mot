<?php

namespace DvsaCommonApi\Formatting;

use DvsaEntities\Entity\Address;

class AddressFormatter
{
    public function format(Address $address)
    {
        return join(', ', array_filter([
            $address->getAddressLine1(),
            $address->getAddressLine2(),
            $address->getAddressLine3(),
            $address->getAddressLine4(),
            $address->getTown(),
            $address->getPostcode()]
        ));
    }
}
