<?php

namespace DvsaCommonTest\Dto\Search;

use DvsaCommon\Constants\SearchParamConst;
use DvsaCommon\Dto\Search\MotTestSearchParamsDto;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommonTest\Dto\AbstractDtoTester;
use Zend\Stdlib\Parameters;

/**
 * Unit test for class MotTestSearchParamsDto
 *
 * @package DvsaCommonTest\Dto\Search
 */
class MotTestSearchParamsDtoTest extends AbstractDtoTester
{
    protected $dtoClassName = MotTestSearchParamsDto::class;

    /**
     * @dataProvider dataProviderTestToQueryParams
     */
    public function testToQueryParams(MotTestSearchParamsDto $dto, array $expect)
    {
        $actual = $dto->toQueryParams();

        $this->assertEquals(
            new Parameters(
                [
                    SearchParamConst::ROW_COUNT => SearchParamConst::DEF_ROWS_COUNT,
                    SearchParamConst::PAGE_NR   => SearchParamConst::DEF_PAGE_NR,
                ] + $expect
            ),
            $actual
        );
    }

    public function dataProviderTestToQueryParams()
    {
        $dateFromTs = 123;
        $dateToTs = 789;

        $dto = new MotTestSearchParamsDto();
        $dto
            ->setDateFromTs($dateFromTs);

        return [
            [
                'dto'    => $dto,
                'params' => [
                    SearchParamConst::SEARCH_DATE_FROM_QUERY_PARAM => $dateFromTs,
                ],
            ],
            [
                'dto'    => $this->cloneObj($dto)
                    ->setDateToTs($dateToTs)
                    ->setTestType(MotTestTypeCode::NORMAL_TEST),
                'params' => [
                    SearchParamConst::SEARCH_DATE_FROM_QUERY_PARAM => $dateFromTs,
                    SearchParamConst::SEARCH_DATE_TO_QUERY_PARAM   => $dateToTs,
                ],
            ],
        ];
    }

    /**
     * @param $obj
     *
     * @return MotTestSearchParamsDto
     */
    private function cloneObj($obj)
    {
        return clone $obj;
    }
}
