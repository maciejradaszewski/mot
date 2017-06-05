<?php

namespace OrganisationApiTest\Service\Mapper;

use DvsaCommon\Dto\Organisation\MotTestLogSummaryDto;
use OrganisationApi\Service\Mapper\MotTestLogSummaryMapper;

/**
 * Class MotTestLogMapperTest.
 */
class MotTestLogMapperTest extends \PHPUnit_Framework_TestCase
{
    /* @var MotTestLogSummaryMapper */
    private $motTestLogMapper;

    public function setUp()
    {
        $this->motTestLogMapper = new MotTestLogSummaryMapper();
    }

    public function testMotTestLogMapperToDtoReturnValue()
    {
        $result = $this->motTestLogMapper->toDto($this->getMotTestLog());

        $this->assertInstanceOf(MotTestLogSummaryDto::class, $result);
        $this->assertEquals('1024', $result->getYear());
        $this->assertEquals('256', $result->getMonth());
        $this->assertEquals('12', $result->getWeek());
        $this->assertEquals('1', $result->getToday());
    }

    protected function getMotTestLog()
    {
        return [
            'year' => '1024',
            'month' => '256',
            'week' => '12',
            'today' => '1',
        ];
    }
}
