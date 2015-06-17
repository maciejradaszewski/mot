<?php

namespace DvsaClientTest\Entity;

use DvsaClient\Entity\ContactDetail;

/**
 * Class ContactDetailTest
 *
 * @package DvsaClientTest\Entity
 */
class ContactDetailTest extends BaseEntityTestCase
{
    public function testSetProperties()
    {
        $expectedProperties = [
            'emails',
            'phones',
            'address',
            'faxNumber',
            'type',
        ];
        $this->checkGettersAndSetters($expectedProperties, new ContactDetail());
    }
}
