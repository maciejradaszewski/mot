<?php

namespace DvsaCommonTest\Dto\Site;

use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Enum\SiteContactTypeCode;

/**
 * Unit test for Site
 */
class SiteContactDtoTest extends \PHPUnit_Framework_TestCase
{
    //  --  other methods are covered by ContactDtoTest --

    public function testGetAndSetType()
    {
        $dto = new SiteContactDto();
        $dto
            ->setId(99999)
            ->setType(SiteContactTypeCode::BUSINESS);

        $this->assertSame(99999, $dto->getId());
        $this->assertSame(SiteContactTypeCode::BUSINESS, $dto->getType());
    }
}
