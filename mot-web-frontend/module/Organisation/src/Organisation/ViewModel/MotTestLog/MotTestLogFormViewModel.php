<?php

namespace Organisation\ViewModel\MotTestLog;

use DvsaClient\ViewModel\AbstractFormModel;
use DvsaClient\ViewModel\DateViewModel;
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

    /**  @var DateViewModel */
    private $dateFrom;
    /**  @var DateViewModel */
    private $dateTo;

    public function __construct()
    {
        $this->setDateFrom(new DateViewModel());
        $this->setDateTo(new DateViewModel());
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
                    new DateViewModel(
                        ArrayUtils::tryGet($date, 'Year'),
                        ArrayUtils::tryGet($date, 'Month'),
                        ArrayUtils::tryGet($date, 'Day')
                    )
                );
            }

            $date = $formData->get(MotTestLogFormViewModel::FLD_DATE_TO, []);

            if (!empty($date)) {
                $this->setDateTo(
                    new DateViewModel(
                        ArrayUtils::tryGet($date, 'Year'),
                        ArrayUtils::tryGet($date, 'Month'),
                        ArrayUtils::tryGet($date, 'Day')
                    )
                );
            }

        } else {
            $date = $formData->get(SearchParamConst::SEARCH_DATE_FROM_QUERY_PARAM);
            if (!empty($date)) {
                $this->setDateFrom((new DateViewModel())->setDate(new \DateTime('@' . $date)));
            }

            $date = $formData->get(SearchParamConst::SEARCH_DATE_TO_QUERY_PARAM);
            if (!empty($date)) {
                $this->setDateTo((new DateViewModel())->setDate(new \DateTime('@' . $date)));
            }
        }

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
     * @return $this
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
     * @return $this
     */
    public function setDateTo(DateViewModel $dateTo)
    {
        $this->dateTo = $dateTo;

        return $this;
    }

    public function isValid()
    {
        $dateFrom = $this->getDateFrom()->getDate();
        $dateTo = $this->getDateTo()->getDate();

        $this->validateDate($dateFrom, MotTestLogFormViewModel::FLD_DATE_FROM);
        $this->validateDate($dateTo, MotTestLogFormViewModel::FLD_DATE_TO);

        if ($dateFrom && $dateTo && $dateFrom > $dateTo) {
            $this->addError(MotTestLogFormViewModel::FLD_DATE_FROM, DateErrors::ERR_DATE_AFTER);
        }

        if ($dateFrom && $dateTo) {
            $this->checkCustomDateRangeNotMoreThan31Days($dateFrom, $dateTo);
        }

        return !$this->hasErrors();
    }

    private function validateDate($date, $field)
    {
        if ($date === null) {
            $this->addError($field, DateErrors::ERR_DATE_MISSING);
        } elseif ($date < (new \DateTime)->setDate(1900, 1, 1)) {
            $this->addError($field, DateErrors::ERR_DATE_INVALID);
        } elseif (DateUtils::isDateInFuture($date)) {
            $this->addError($field, DateErrors::ERR_DATE_INVALID);
        }
    }

    private function checkCustomDateRangeNotMoreThan31Days(\DateTime $dateFrom, \DateTime $dateTo)
    {
        if ($dateFrom->diff($dateTo)->days > self::VALIDATION_MAX_DAYS ) {
            $this->addError(MotTestLogFormViewModel::FLD_DATE_FROM, DateErrors::ERR_DATE_RANGE);
        }
    }
}
