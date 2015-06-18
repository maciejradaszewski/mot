<?php

namespace Organisation\ViewModel\MotTestLog;

use DvsaClient\ViewModel\AbstractFormModel;
use DvsaClient\ViewModel\DateTimeViewModel;
use DvsaCommon\Constants\SearchParamConst;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Messages\DateErrors;
use DvsaCommon\Utility\ArrayUtils;
use Zend\Stdlib\Parameters;

class MotTestLogFormViewModel extends AbstractFormModel
{
    const FLD_DATE_FROM = 'dateFrom';
    const FLD_DATE_TO = 'dateTo';

    const VALIDATION_MAX_DAYS = 31;

    /**
     * @var DateTimeViewModel
     */
    private $dateFrom;
    /**
     * @var DateTimeViewModel
     */
    private $dateTo;

    public function __construct()
    {
        $this->setDateFrom(new DateTimeViewModel());
        $this->setDateTo(new DateTimeViewModel());
    }

    /**
     * Map field values from POST/GET data
     *
     * @param Parameters $formData
     *
     * @return MotTestLogViewModel
     */
    public function parseData(Parameters $formData)
    {
        if ($formData->get('_csrf_token', false)) {
            $date = $formData->get(MotTestLogFormViewModel::FLD_DATE_FROM, []);

            if (!empty($date)) {
                $this->setDateFrom(
                    new DateTimeViewModel(
                        ArrayUtils::tryGet($date, 'Year'),
                        ArrayUtils::tryGet($date, 'Month'),
                        ArrayUtils::tryGet($date, 'Day')
                    )
                );
            }

            $date = $formData->get(MotTestLogFormViewModel::FLD_DATE_TO, []);

            if (!empty($date)) {
                $this->setDateTo(
                    new DateTimeViewModel(
                        ArrayUtils::tryGet($date, 'Year'),
                        ArrayUtils::tryGet($date, 'Month'),
                        ArrayUtils::tryGet($date, 'Day'),
                        23, 59, 59
                    )
                );
            }

        } else {
            $date = $formData->get(SearchParamConst::SEARCH_DATE_FROM_QUERY_PARAM);
            if (!empty($date)) {
                $this->setDateFrom((new DateTimeViewModel())->setDate(new \DateTime('@' . $date)));
            }

            $date = $formData->get(SearchParamConst::SEARCH_DATE_TO_QUERY_PARAM);
            if (!empty($date)) {
                $this->setDateTo((new DateTimeViewModel())->setDate(new \DateTime('@' . $date)));
            }
        }

        return $this;
    }

    /**
     * @return DateTimeViewModel
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * @param DateTimeViewModel $dateFrom
     *
     * @return $this
     */
    public function setDateFrom(DateTimeViewModel $dateFrom)
    {
        $this->dateFrom = $dateFrom;
        return $this;
    }

    /**
     * @return DateTimeViewModel
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * @param DateTimeViewModel $dateTo
     *
     * @return $this
     */
    public function setDateTo(DateTimeViewModel $dateTo)
    {
        $this->dateTo = $dateTo;
        return $this;
    }

    public function isValid()
    {
        $this->validateDate($this->getDateFrom(), MotTestLogFormViewModel::FLD_DATE_FROM);
        $this->validateDate($this->getDateTo(), MotTestLogFormViewModel::FLD_DATE_TO);

        $dateFrom = $this->getDateFrom()->getDate();
        $dateTo = $this->getDateTo()->getDate();

        if ($dateFrom && $dateTo) {
            if ($dateFrom > $dateTo) {
                $this->addError(MotTestLogFormViewModel::FLD_DATE_FROM, DateErrors::AFTER_TO);
            }

            if ($dateFrom->diff($dateTo)->days > self::VALIDATION_MAX_DAYS) {
                $this->addError(MotTestLogFormViewModel::FLD_DATE_FROM, DateErrors::RANGE_31D);
            }
        }

        return !$this->hasErrors();
    }

    private function validateDate(DateTimeViewModel $model, $field)
    {
        $date = $model->getDate();

        if (trim($model->getYear()) === '' || trim($model->getMonth())  === '' || trim($model->getDay()) === '') {
            $this->addError($field, DateErrors::INVALID_FORMAT);
        } elseif (
            $date === null
            || $date < new \DateTime('1900-01-01')
        ) {
            $this->addError($field, DateErrors::NOT_EXIST);
        } elseif (DateUtils::isDateInFuture($date)) {
            $this->addError($field, DateErrors::IN_FUTURE);
        }
    }
}
