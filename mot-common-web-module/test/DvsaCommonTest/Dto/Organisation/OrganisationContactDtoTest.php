<?php

namespace DvsaCommonTest\Organisation\Dto;

use DvsaCommon\Dto\Organisation\OrganisationContactDto;

/**
 * unit tests for OrganisationContactDto
 */
class OrganisationContactDtoTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $orgContact = new OrganisationContactDto();
        $orgContact->setType('aaa');
        $this->assertEquals('aaa', $orgContact->getType());
    }
}
