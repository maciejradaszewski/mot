<?php

namespace Organisation\ViewModel\MotTestLog;

use DvsaClient\ViewModel\DateViewModel;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Utility\ArrayUtils;

class MotTestLogFormViewModel
{
    const FLD_DATE_FROM = 'dateFrom';
    const FLD_DATE_TO = 'dateTo';

    /**  @var DateViewModel */
    private $dateFrom;
    /**  @var DateViewModel */
    private $dateTo;

    public function __construct(array $formData = null)
    {
        $this->parseData($formData);
    }

    /**
     * Map field values from POST data
     *
     * @param $postData
     *
     * return MotTestLogViewModel
     */
    public function parseData($postData)
    {
        $date = ArrayUtils::tryGet($postData, MotTestLogFormViewModel::FLD_DATE_FROM, []);
        $this->setDateFrom(
            new DateViewModel(
                ArrayUtils::tryGet($date, 'Year'),
                ArrayUtils::tryGet($date, 'Month'),
                ArrayUtils::tryGet($date, 'Day')
            )
        );

        $date = ArrayUtils::tryGet($postData, MotTestLogFormViewModel::FLD_DATE_TO, []);
        $this->setDateTo(
            new DateViewModel(
                ArrayUtils::tryGet($date, 'Year'),
                ArrayUtils::tryGet($date, 'Month'),
                ArrayUtils::tryGet($date, 'Day')
            )
        );

        return $this;
    }


    /**
     * Parse query params
     *
     * @param $postData
     *
     * return MotTestLogViewModel
     */
    public function parseQuery(array $queryData)
    {
        $date = ArrayUtils::tryGet($queryData, MotTestLogFormViewModel::FLD_DATE_FROM);
        $this->setDateFrom(
            (new DateViewModel())->setDate(DateUtils::toDate($date))
        );

        $date = ArrayUtils::tryGet($queryData, MotTestLogFormViewModel::FLD_DATE_TO);
        $this->setDateTo(
            (new DateViewModel())->setDate(DateUtils::toDate($date))
        );

        return $this;
    }

    /**
     * @return DateViewModel
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * @param DateViewModel $dateFrom
     *
     * return MotTestLogViewModel
     */
    public function setDateFrom(DateViewModel $dateFrom)
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    /**
     * @return DateViewModel
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * @param DateViewModel $dateTo
     *
     * return MotTestLogViewModel
     */
    public function setDateTo(DateViewModel $dateTo)
    {
        $this->dateTo = $dateTo;

        return $this;
    }
}
