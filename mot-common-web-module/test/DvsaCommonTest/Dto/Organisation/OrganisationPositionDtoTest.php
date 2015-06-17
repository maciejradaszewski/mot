<?php

namespace DvsaCommonTest\Organisation\Dto;

use DvsaCommon\Dto\Organisation\OrganisationPositionDto;
use DvsaCommon\Dto\Person\PersonDto;
use DvsaCommon\Enum\BusinessRoleStatusCode;
use DvsaCommon\Enum\OrganisationBusinessRoleCode;

/**
 * Unit tests for OrganisationPositionDto
 */
class OrganisationPositionDtoTest extends \PHPUnit_Framework_TestCase
{
    const ID = 1;
    const ACTIONED_ON = "2011-01-01 12:00:00";
    const STATUS = BusinessRoleStatusCode::ACCEPTED;

    public function testGetters()
    {
        $orgPos = $this->organisationPosition();
        $this->assertEquals(self::ID, $orgPos->getId());
        $this->assertEquals(self::ACTIONED_ON, $orgPos->getActionedOn());
        $this->assertEquals(self::STATUS, $orgPos->getStatus());
        $this->assertNotNull($orgPos->getRole());
        $this->assertNotNull($orgPos->getPerson());
    }

    public function testIsPending()
    {
        $this->assertTrue(
            $this->organisationPosition()->setStatus(BusinessRoleStatusCode::PENDING)->isPending()
        );
    }

    private function organisationPosition()
    {
        $user = new PersonDto();
        return (new OrganisationPositionDto())
            ->setId(self::ID)
            ->setActionedOn(self::ACTIONED_ON)
            ->setPerson($user)
            ->setRole(OrganisationBusinessRoleCode::AUTHORISED_EXAMINER_DELEGATE)
            ->setStatus(self::STATUS);
    }
}
