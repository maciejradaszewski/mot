<?php

namespace DvsaCommonTest\Dto\Common;

use DvsaCommon\Dto\Common\ReasonForRefusalDto;
use DvsaCommon\Enum\LanguageTypeCode;
use DvsaCommonTest\Dto\AbstractDtoTester;

/**
 * Unit test for class ReasonForRefusalDto
 */
class ReasonForRefusalDtoTest extends AbstractDtoTester
{
    protected $dtoClassName = ReasonForRefusalDto::class;

    public function testGetReasonInLang()
    {
        $dto = new ReasonForRefusalDto();
        $dto->setReason('text in english');
        $dto->setReasonCy('text in welsh');

        $this->assertEquals('text in english', $dto->getReasonInLang());
        $this->assertEquals('text in welsh', $dto->getReasonInLang(LanguageTypeCode::WELSH));
    }
}
