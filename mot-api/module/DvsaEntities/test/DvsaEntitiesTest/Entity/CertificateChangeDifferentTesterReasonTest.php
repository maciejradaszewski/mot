<?php
namespace DvsaEntitiesTest\Entity;

use DvsaEntitiesTest\Entity\BaseEntityTestCase;
use DvsaEntities\Entity\CertificateChangeDifferentTesterReason;

/**
 * Class CertificateChangeDifferentTesterReasonTest
 */
class CertificateChangeDifferentTesterReasonTest extends BaseEntityTestCase
{
    public function testSetsPropertiesCorrectly()
    {
        $expectedProperties = [
            'description',
        ];
        $this->checkGettersAndSetters($expectedProperties, new CertificateChangeDifferentTesterReason());
    }
}
