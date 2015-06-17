<?php
namespace DvsaEntitiesTest\Entity;

use DvsaEntitiesTest\Entity\BaseEntityTestCase;
use DvsaEntities\Entity\SpecialNoticeContent;

/**
 * Class SpecialNoticeContentTest
 */
class SpecialNoticeContentTest extends BaseEntityTestCase
{
    public function testSetProperites()
    {
        $expectedProperties = [
            'title',
            'noticeText',
            'issueDate',
            'issueNumber',
            'issueYear',
            'expiryDate',
            'internalPublishDate',
            'externalPublishDate',
        ];
        $this->checkGettersAndSetters($expectedProperties, new SpecialNoticeContent());
    }
}
