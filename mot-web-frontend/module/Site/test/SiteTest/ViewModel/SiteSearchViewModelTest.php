<?php

namespace SiteTest\ViewModel;

use DvsaCommon\Dto\Search\SiteSearchParamsDto;
use DvsaCommon\Dto\Site\SiteListDto;
use DvsaCommon\Dto\Site\SiteSearchDto;
use DvsaCommon\UrlBuilder\SiteUrlBuilderWeb;
use Site\ViewModel\SiteSearchViewModel;

/**
 * Class SiteSearchViewModelTest
 * @package SiteTest\ViewModel
 */
class SiteSearchViewModelTest extends \PHPUnit_Framework_TestCase
{
    const SITE_NUMBER = 'V1';
    const SITE_NAME = 'Garage';
    const SITE_TOWN = 'Toulouse';
    const SITE_POSTCODE = 'AAA ZZZ';

    public function testGetterSetter()
    {
        $model = new SiteSearchViewModel();

        $model->populateFromPost(
            [
                SiteSearchViewModel::FIELD_SITE_NUMBER => self::SITE_NUMBER,
                SiteSearchViewModel::FIELD_SITE_NAME => self::SITE_NAME,
                SiteSearchViewModel::FIELD_SITE_TOWN => self::SITE_TOWN,
                SiteSearchViewModel::FIELD_SITE_POSTCODE => self::SITE_POSTCODE,
                SiteSearchViewModel::FIELD_SITE_VEHICLE_CLASS => []
            ]
        );

        $this->assertInstanceOf(
            SiteSearchViewModel::class,
            $model->setSiteList(
                (new SiteListDto())
                    ->setSites([new SiteSearchDto()])
                    ->setTotalResult(2)
            )
        );
        $this->assertInstanceOf(SiteSearchDto::class, $model->getSites()[0]);
        $this->assertEquals(2, $model->getTotalResults());

        $this->assertEquals(self::SITE_NUMBER, $model->getSiteNumber());
        $this->assertEquals(self::SITE_NAME, $model->getSiteName());
        $this->assertEquals(self::SITE_TOWN, $model->getSiteTown());
        $this->assertEquals(self::SITE_POSTCODE, $model->getSitePostcode());
        $this->assertEquals([], $model->getSiteVehicleClass());
        $this->assertEquals($this->getSiteVehicleClassParameters(), $model->getSiteVehicleClassParameters());

        $this->assertEquals('V1, Garage, Toulouse, AAA ZZZ', $model->displaySearchCriteria());

        $this->assertInstanceOf(SiteSearchParamsDto::class, $model->prepareSearchParams());

        $this->assertEquals(SiteUrlBuilderWeb::search(), $model->getSearchPage());
        $this->assertEquals(SiteUrlBuilderWeb::result(), $model->getResultPage());

        $this->assertTrue($model->isValid());
    }

    public function testInvalidForm()
    {
        $model = new SiteSearchViewModel();

        $this->assertFalse($model->isValid());
        $this->assertEquals(
            SiteSearchViewModel::ONE_FIELD_REQUIRED,
            $model->getError(SiteSearchViewModel::FIELD_SITE_NUMBER)
        );
    }

    private function getSiteVehicleClassParameters()
    {
        return [
            [
                'value'     => '1',
                'inputName' => SiteSearchViewModel::FIELD_SITE_VEHICLE_CLASS . '[]',
                'key'       => 'Class 1',
                'checked'   => false,
            ],
            [
                'value'     => '2',
                'inputName' => SiteSearchViewModel::FIELD_SITE_VEHICLE_CLASS . '[]',
                'key'       => 'Class 2',
                'checked'   => false,
            ],
            [
                'value'     => '3',
                'inputName' => SiteSearchViewModel::FIELD_SITE_VEHICLE_CLASS . '[]',
                'key'       => 'Class 3',
                'checked'   => false,
            ],
            [
                'value'     => '4',
                'inputName' => SiteSearchViewModel::FIELD_SITE_VEHICLE_CLASS . '[]',
                'key'       => 'Class 4',
                'checked'   => false,
            ],
            [
                'value'     => '5',
                'inputName' => SiteSearchViewModel::FIELD_SITE_VEHICLE_CLASS . '[]',
                'key'       => 'Class 5',
                'checked'   => false,
            ],
            [
                'value'     => '7',
                'inputName' => SiteSearchViewModel::FIELD_SITE_VEHICLE_CLASS . '[]',
                'key'       => 'Class 7',
                'checked'   => false,
            ],
        ];
    }
}
