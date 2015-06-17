<?php

namespace DvsaCommonTest\Dto\Search;

use DvsaCommon\Dto\Search\SearchResultDto;

/**
 * Unit test for class SearchResultDtoTest
 *
 * @package DvsaCommonTest\Dto\Search
 */
class SearchResultDtoTest extends \PHPUnit_Framework_TestCase
{
    /* @var SearchResultDto */
    protected $searchResultDto;
    protected $searchResultDtoClass = SearchResultDto::class;

    public function testSetterGetterSearchResultDto()
    {
        $this->searchResultDto = new SearchResultDto();
        $this->assertInstanceOf($this->searchResultDtoClass, $this->searchResultDto->setData(['test' => 'dto']));
        $this->assertInstanceOf($this->searchResultDtoClass, $this->searchResultDto->setIsElasticSearch(true));
        $this->assertInstanceOf($this->searchResultDtoClass, $this->searchResultDto->setResultCount(10));
        $this->assertInstanceOf($this->searchResultDtoClass, $this->searchResultDto->setSearched('searched'));
        $this->assertInstanceOf($this->searchResultDtoClass, $this->searchResultDto->setTotalResultCount(10));

        $this->assertEquals(['test' => 'dto'], $this->searchResultDto->getData());
        $this->assertEquals(true, $this->searchResultDto->isElasticSearch());
        $this->assertEquals(10, $this->searchResultDto->getResultCount());
        $this->assertEquals('searched', $this->searchResultDto->getSearched());
        $this->assertEquals(10, $this->searchResultDto->getTotalResultCount());
    }

}
