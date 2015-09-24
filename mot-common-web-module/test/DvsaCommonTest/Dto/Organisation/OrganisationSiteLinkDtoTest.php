<?php

namespace DvsaCommonTest\Dto\Organisation;

use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Dto\Organisation\OrganisationSiteLinkDto;
use DvsaCommon\Dto\Site\SiteDto;
use DvsaCommonTest\Dto\AbstractDtoTester;

/**
 * Unit test for class OrganisationSiteLinkDto
 *
 * @package DvsaCommonTest\Dto\Common
 */
class OrganisationSiteLinkDtoTest extends AbstractDtoTester
{
    protected $dtoClassName = OrganisationSiteLinkDto::class;

    public function providerGettersAndSetters()
    {
        $testMethods = parent::providerGettersAndSetters();

        array_push(
            $testMethods,
            ['site', (new SiteDto())->setId(1)],
            ['organisation', (new OrganisationDto())->setId(1)]
        );

        return $testMethods;
    }
}
