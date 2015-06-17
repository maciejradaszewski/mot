<?php

namespace DvsaCommonTest\Dto\Common;

use DvsaCommon\Dto\Common\ReasonForCancelDto;
use DvsaCommon\Enum\LanguageTypeCode;
use DvsaCommonTest\Dto\AbstractDtoTester;

/**
 * Unit test for class ReasonForCancelDto
 *
 * @package DvsaCommonTest\Dto\Common
 */
class ReasonForCancelDtoTest extends AbstractDtoTester
{
    protected $dtoClassName = ReasonForCancelDto::class;

    public function testGetReasonInLang()
    {
        $dto = new ReasonForCancelDto();
        $dto->setReason('text in english');
        $dto->setReasonCy('text in welsh');

        $this->assertEquals('text in english', $dto->getReasonInLang());
        $this->assertEquals('text in welsh', $dto->getReasonInLang(LanguageTypeCode::WELSH));
    }
}
