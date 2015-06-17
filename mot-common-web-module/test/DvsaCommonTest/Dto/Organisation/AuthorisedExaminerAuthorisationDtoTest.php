<?php

namespace DvsaCommonTest\Organisation\Dto;

use DvsaCommon\Dto\Common\AuthForAeStatusDto;
use DvsaCommon\Dto\Organisation\AuthorisedExaminerAuthorisationDto;
use DvsaCommon\Enum\AuthorisationForAuthorisedExaminerStatusCode;

/**
 * unit tests for AuthorisedExaminerAuthorisationDto
 */
class AuthorisedExaminerAuthorisationDtoTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $expectRefNr = 'B99999';
        $expectValidFrom = new \DateTime('2014-01-02');
        $expectExpiryDate = new \DateTime('2011-12-13');

        $expectStatusDto = new AuthForAeStatusDto();
        $expectStatusDto
            ->setCode(AuthorisationForAuthorisedExaminerStatusCode::APPLIED)
            ->setName('IN PROGRESS');

        //  --  set expected values --
        $aeAuthDto = new AuthorisedExaminerAuthorisationDto();
        $aeAuthDto
            ->setAuthorisedExaminerRef($expectRefNr)
            ->setValidFrom($expectValidFrom)
            ->setExpiryDate($expectExpiryDate)
            ->setStatus($expectStatusDto);

        //  --  check expected and actial values    --
        $this->assertEquals($expectRefNr, $aeAuthDto->getAuthorisedExaminerRef());
        $this->assertEquals($expectValidFrom, $aeAuthDto->getValidFrom());
        $this->assertEquals($expectExpiryDate, $aeAuthDto->getExpiryDate());

        $actualStatusDto = $aeAuthDto->getStatus();
        $this->assertEquals($expectStatusDto->getCode(), $actualStatusDto->getCode());
        $this->assertEquals($expectStatusDto->getName(), $actualStatusDto->getName());
    }
}
