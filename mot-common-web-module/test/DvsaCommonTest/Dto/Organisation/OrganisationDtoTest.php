<?php

namespace DvsaCommonTest\Dto\Organisation;

use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommonTest\Dto\AbstractDtoTester;

/**
 * Unit test for class OrganisationDto
 *
 * @package DvsaCommonTest\Dto\Common
 */
class OrganisationDtoTest extends AbstractDtoTester
{
    protected $dtoClassName = OrganisationDto::class;

    public function testGetContactsByType()
    {
        //  --  given   --
        $corrContact = (new OrganisationContactDto())
            ->setAddress(new AddressDto())
            ->setType(OrganisationContactTypeCode::CORRESPONDENCE);

        $busContact = (new OrganisationContactDto())
            ->setAddress(new AddressDto())
            ->setType(OrganisationContactTypeCode::REGISTERED_COMPANY);

        $dto = new OrganisationDto();
        $dto->setContacts([$busContact, $corrContact]);

        //  --  check   --
        $this->assertSame($corrContact, $dto->getContactByType(OrganisationContactTypeCode::CORRESPONDENCE));
        $this->assertSame($busContact, $dto->getContactByType(OrganisationContactTypeCode::REGISTERED_COMPANY));
        $this->assertSame($busContact, $dto->getRegisteredCompanyContactDetail());
    }
}
