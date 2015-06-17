<?php

namespace DvsaCommonTest\Dto\Site;

use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Dto\Site\SiteDto;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommonTest\Dto\AbstractDtoTester;

/**
 * Unit test for SiteDto class
 *
 * @package DvsaCommonTest\Dto\Equipment
 */
class SiteDtoTest extends AbstractDtoTester
{
    protected $dtoClassName = SiteDto::class;

    public function testAddContact()
    {
        $expectContactBus = new SiteContactDto();
        $expectContactBus->setType(SiteContactTypeCode::BUSINESS);

        $expectContactCorr = new SiteContactDto();
        $expectContactCorr->setType(SiteContactTypeCode::CORRESPONDENCE);

        $dto = new SiteDto();
        $dto->addContact($expectContactBus);
        $dto->addContact($expectContactCorr);

        $this->assertSame($expectContactBus, $dto->getContacts()[0]);
        $this->assertSame($expectContactCorr, $dto->getContactByType(SiteContactTypeCode::CORRESPONDENCE));
    }
}
