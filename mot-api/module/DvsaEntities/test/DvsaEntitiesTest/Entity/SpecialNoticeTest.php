<?php
namespace DvsaEntitiesTest\Entity;

use DvsaEntitiesTest\Entity\BaseEntityTestCase;
use DvsaEntities\Entity\SpecialNotice;

/**
 * Class SpecialNoticeTest
 *
 * @package DvsaEntitiesTest\Entity
 */
class SpecialNoticeTest extends BaseEntityTestCase
{
    public function testSetProperites()
    {
        $expectedProperties = [
            'username',
            'content',
            'isAcknowledged',
        ];
        $this->checkGettersAndSetters($expectedProperties, new SpecialNotice());
    }
}
