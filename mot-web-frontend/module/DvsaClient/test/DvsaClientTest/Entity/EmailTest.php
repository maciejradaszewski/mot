<?php

namespace DvsaClientTest\Entity;

use DvsaClient\Entity\Email;

/**
 * Class EmailTest
 *
 * @package DvsaClientTest\Entity
 */
class EmailTest extends BaseEntityTestCase
{
    public function testSetProperties()
    {
        $expectedProperties = [
            'id',
            'contactType',
            'email',
            'isPrimary',
        ];
        $this->checkGettersAndSetters($expectedProperties, new Email());
    }
}
