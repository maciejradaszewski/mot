<?php

namespace DvsaClientTest\Entity;

use DvsaClient\Entity\Phone;

/**
 * Class PhoneTest
 *
 * @package DvsaClientTest\Entity
 */
class PhoneTest extends BaseEntityTestCase
{
    public function testSetProperties()
    {
        $expectedProperties = [
            'id',
            'contactType',
            'number',
            'isPrimary',
        ];
        $this->checkGettersAndSetters($expectedProperties, new Phone());
    }
}
