<?php

namespace DvsaClientTest\Mapper;

use DvsaClient\Mapper\MotTestLogMapper;
use DvsaCommon\Dto\Organisation\MotTestLogSummaryDto;
use DvsaCommon\Dto\Search\MotTestSearchParamsDto;
use DvsaCommon\Dto\Search\SearchResultDto;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilder;
use DvsaCommon\Utility\DtoHydrator;

class MotTestLogMapperTest extends AbstractMapperTest
{
    const ORG_ID = 99999;

    /** @var MotTestLogMapper $mapper */
    private $mapper;

    public function setUp()
    {
        parent::setUp();

        $this->mapper = new MotTestLogMapper($this->client);
    }

    public function testGetSummary()
    {
        $expectDto = (new MotTestLogSummaryDto())
            ->setToday(1)
            ->setWeek(2)
            ->setMonth(2)
            ->setYear(9);

        $this->setupClientMockGet(
            AuthorisedExaminerUrlBuilder::motTestLogSummary(self::ORG_ID),
            ['data' => DtoHydrator::dtoToJson($expectDto)]
        );
        $actualDto = $this->mapper->getSummary(self::ORG_ID);

        $this->assertEquals($expectDto, $actualDto);
    }

    public function testGetData()
    {
        $searchParamsDto = new MotTestSearchParamsDto();

        $expectDto = (new SearchResultDto())
            ->setTotalResultCount(1)
            ->setData(['key' => 'value']);

        $this->setupClientMockPost(
            AuthorisedExaminerUrlBuilder::motTestLog(self::ORG_ID),
            DtoHydrator::dtoToJson($searchParamsDto),
            ['data' => DtoHydrator::dtoToJson($expectDto)]
        );

        $actualDto = $this->mapper->getData(self::ORG_ID, $searchParamsDto);

        $this->assertEquals($expectDto, $actualDto);
    }
}
