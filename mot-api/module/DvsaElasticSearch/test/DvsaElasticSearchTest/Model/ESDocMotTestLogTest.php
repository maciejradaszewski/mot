<?php

namespace DvsaElasticSearchTest\Model;

use Doctrine\Common\Collections\ArrayCollection;
use DvsaCommon\Constants\SearchParamConst;
use DvsaCommon\Dto\Search\SearchParamsDto;
use DvsaCommon\Dto\Search\SearchResultDto;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaCommonTest\TestUtils\XMock;
use DvsaElasticSearch\Model\ESDocMotTestLog;
use PHPUnit_Framework_TestCase;

/**
 * Class ESDocMotTestLogTest
 *
 * @package DvsaElasticSearchTest\Connection
 */
class ESDocMotTestLogTest extends \PHPUnit_Framework_TestCase
{
    const INVALID_FORMAT = 'invalid-Format';

    /** @var ESDocMotTestLog */
    protected $docMotTestLog;

    protected $mockSearchResultDto;
    protected $mockSearchParamsDto;

    public function setUp()
    {
        $this->mockSearchResultDto = XMock::of(SearchResultDto::class, ['getSearched', 'isElasticSearch']);
        $this->mockSearchParamsDto = XMock::of(SearchParamsDto::class, ['getFormat']);

        $this->docMotTestLog = new ESDocMotTestLog();
    }

    public function testEsDocMotTestLogAsEsDataReturnValue()
    {
        $this->mockSearchResultDto->expects($this->any())
            ->method('getSearched')
            ->willReturn($this->mockSearchParamsDto);

        $this->mockSearchParamsDto->expects($this->any())
            ->method('getFormat')
            ->willReturn(SearchParamConst::FORMAT_DATA_CSV);

        $this->assertSame([], $this->docMotTestLog->asJson($this->mockSearchResultDto));
    }

    public function testEsDocMotTestAsJsonReturnException()
    {
        $this->mockSearchResultDto->expects($this->any())
            ->method('getSearched')
            ->willReturn($this->mockSearchParamsDto);

        $this->mockSearchParamsDto->expects($this->any())
            ->method('getFormat')
            ->willReturn(self::INVALID_FORMAT);

        $this->setExpectedException(BadRequestException::class);
        $this->docMotTestLog->asJson($this->mockSearchResultDto);
    }
}
