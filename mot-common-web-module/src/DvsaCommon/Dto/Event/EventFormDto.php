<?php

namespace DvsaCommon\Dto\Event;

use DvsaCommon\Dto\Common\DateDto;
use DvsaCommon\Dto\DataTable\DataTableDto;
use DvsaCommon\Utility\ArrayUtils;

/**
 * Class EventFormDto
 * @package DvsaCommon\Dto\Event
 */
class EventFormDto extends DataTableDto
{
    const FLD_DATE_FROM     = 'dateFrom';
    const FLD_DATE_TO       = 'dateTo';
    const FLD_SEARCH        = 'search';
    const FLD_SHOW_DATE     = 'isShowDate';


    static public $dbSortByColumns = [
        "0" => "et.description", // event_type
        "1" => "e.eventDate", // event
        "2" => "e.shortDescription", // event
    ];

    /**  @var DateDto */
    private $dateFrom;
    /**  @var DateDto */
    private $dateTo;
    /**  @var string */
    private $search;
    /**  @var boolean */
    private $isShowDate;

    /**
     * This function create a new object, if the data are passed, we build the parameters
     *
     * @param array $formData
     */
    public function __construct(array $formData = null)
    {
        if ($formData !== null) {
            $this->parseData($formData);
        }
    }

    /**
     * @param $data
     * @return $this
     */
    public function parseData($data)
    {
        $date = ArrayUtils::tryGet($data, self::FLD_DATE_FROM, []);
        $this->setDateFrom(
            new DateDto(
                ArrayUtils::tryGet($date, 'Year'),
                ArrayUtils::tryGet($date, 'Month'),
                ArrayUtils::tryGet($date, 'Day')
            )
        );

        $date = ArrayUtils::tryGet($data, self::FLD_DATE_TO, []);
        $this->setDateTo(
            new DateDto(
                ArrayUtils::tryGet($date, 'Year'),
                ArrayUtils::tryGet($date, 'Month'),
                ArrayUtils::tryGet($date, 'Day')
            )
        );

        $this->setSearch(ArrayUtils::tryGet($data, self::FLD_SEARCH, ''));
        $this->setIsShowDate(ArrayUtils::tryGet($data, self::FLD_SHOW_DATE, false));
        $this->setDisplayStart(ArrayUtils::tryGet($data, self::FLD_DISPLAY_START, 0));
        $this->setDisplayLength(ArrayUtils::tryGet($data, self::FLD_DISPLAY_LENGTH, 10));
        $this->setSortCol(ArrayUtils::tryGet($data, self::FLD_SORT_COL, 1));
        $this->setSortDir(ArrayUtils::tryGet($data, self::FLD_SORT_DIR, 'DESC'));

        return $this;
    }

    /**
     * @return DateDto
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * @param DateDto $dateFrom
     * @return $this
     */
    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = $dateFrom;
        return $this;
    }

    /**
     * @return DateDto
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * @param DateDto $dateTo
     * @return $this
     */
    public function setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;
        return $this;
    }

    /**
     * @return string
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param string $search
     * @return $this
     */
    public function setSearch($search)
    {
        $this->search = $search;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isShowDate()
    {
        return (boolean)$this->isShowDate;
    }

    /**
     * @param boolean $isShowDate
     * @return $this
     */
    public function setIsShowDate($isShowDate)
    {
        $this->isShowDate = $isShowDate;
        return $this;
    }

    /**
     * This function is responsible to convert the object to an array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'search'        => $this->search,
            'isShowDate'    => $this->isShowDate,
            'dateFrom'      => [
                'Day'   => $this->dateFrom->getDay(),
                'Month' => $this->dateFrom->getMonth(),
                'Year'  => $this->dateFrom->getYear(),
            ],
            'dateTo'        => [
                'Day'   => $this->dateTo->getDay(),
                'Month' => $this->dateTo->getMonth(),
                'Year'  => $this->dateTo->getYear(),
            ],
         ];
    }
}
