<?php

namespace DvsaCommonTest\Dto\Event;

use DvsaCommon\Dto\Common\DateDto;
use DvsaCommon\Dto\Event\EventFormDto;

/**
 * Class EventFormDtoTest
 */
class EventFormDtoTest extends \PHPUnit_Framework_TestCase
{
    const AE_ID = 9;
    const AE_NUMBER = 'A1234';
    const AE_NAME = 'Organisation name';

    const SITE_ID = 1;
    const SITE_NUMBER = 'V1234';
    const SITE_NAME = 'Site name';

    const PERSON_ID = 5;
    const PERSON_USERNAME = 'tester1';
    const PERSON_FIRSTNAME = 'Firstname';
    const PERSON_MIDDLENAME = 'Middlename';
    const PERSON_FAMILYNAME = 'Familyname';

    /**
     * @var EventFormDtoTest
     */
    private $formViewModel;

    public function testCreateNewEventFormViewModel()
    {
        $query = [
            'isShowDate' => true,
            'dateFrom' => [
                'Day' => '01',
                'Month' => '01',
                'Year' => '2014'
            ],
            'dateTo' => [
                'Day' => '01',
                'Month' => '01',
                'Year' => '2015'
            ],
            'search' => 'search',
        ];
        $this->formViewModel = new EventFormDto($query);

        $this->assertInstanceOf(EventFormDto::class, $this->formViewModel);
        $this->assertInstanceOf(DateDto::class, $this->formViewModel->getDateFrom());
        $this->assertInstanceOf(DateDto::class, $this->formViewModel->getDateTo());

        $this->assertSame('01', $this->formViewModel->getDateFrom()->getDay());
        $this->assertSame('01', $this->formViewModel->getDateFrom()->getMonth());
        $this->assertSame('2014', $this->formViewModel->getDateFrom()->getYear());

        $this->assertSame('01', $this->formViewModel->getDateTo()->getDay());
        $this->assertSame('01', $this->formViewModel->getDateTo()->getMonth());
        $this->assertSame('2015', $this->formViewModel->getDateTo()->getYear());

        $this->assertSame('search', $this->formViewModel->getSearch());

        $this->assertTrue($this->formViewModel->isShowDate());
        $this->assertEquals($query, $this->formViewModel->toArray());
    }
}
