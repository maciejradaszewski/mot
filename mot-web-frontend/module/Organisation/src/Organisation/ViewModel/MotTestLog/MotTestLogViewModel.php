<?php

namespace Organisation\ViewModel\MotTestLog;

use DvsaCommon\Constants\SearchParamConst;
use DvsaCommon\Dto\Organisation\MotTestLogSummaryDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use Organisation\ViewModel\MotTestLog\Formatter\VehicleModelSubRow;
use Report\Filter\FilterBuilder;
use Report\Table\Formatter\MotTestLink;
use Report\Table\Formatter\SubRow;
use Report\Table\Table;
use Zend\Stdlib\Parameters;

class MotTestLogViewModel
{
    /** @var MotTestLogSummaryDto */
    private $logSummary;
    /** @var OrganisationDto */
    private $organisation;
    /** @var MotTestLogFormViewModel */
    private $formModel;
    /** @var  Table */
    private $table;
    /** @var  FilterBuilder */
    private $filterBuilder;

    public function __construct(
        OrganisationDto $org,
        MotTestLogSummaryDto $logData
    ) {
        $this->setOrganisation($org);
        $this->setMotTestLogSummary($logData);
        $this->setFormModel(new MotTestLogFormViewModel());

        $this->defineTable();
        $this->defineFilterBuilder();

        $this->setDefaultValues();
    }

    private function setDefaultValues()
    {
        $defValues = new Parameters(
            [
                //  monday last week
                SearchParamConst::SEARCH_DATE_FROM_QUERY_PARAM => strtotime('monday this week - 7 days'),
                //  sunday last week
                SearchParamConst::SEARCH_DATE_TO_QUERY_PARAM   => strtotime('monday this week - 1 second'),
            ]
        );

        $this->parseData($defValues);
    }

    public function parseData(Parameters $paramData)
    {
        if ($paramData->count() > 0) {
            $this->getFormModel()->parseData($paramData);
            $this->getFilterBuilder()->setQueryParams($paramData);
        }
    }

    public function getDownloadUrl()
    {
        return AuthorisedExaminerUrlBuilderWeb::motTestLogDownloadCsv($this->organisation->getId())
            ->queryParams(
                [
                    SearchParamConst::SEARCH_DATE_FROM_QUERY_PARAM =>
                        $this->formModel->getDateFrom()->getDate()->getTimestamp(),
                    SearchParamConst::SEARCH_DATE_TO_QUERY_PARAM   =>
                        $this->formModel->getDateTo()->getDate()->getTimestamp(),
                ]
            )->toString();
    }

    private function defineTable()
    {
        $this->table = new Table();
        $this->table->setColumns(
            [
                [
                    'title'   => 'Date/time',
                    'sortBy'  => 'testDateTime',
                    'sub'    => [
                        [
                            'field'     => 'testDate',
                        ],
                        [
                            'field'     => 'testTime',
                            'formatter' => SubRow::class,
                        ],
                    ]
                ],
                [
                    'field'  => 'vehicleVRM',
                    'title'  => 'VRM',
                    'sortBy' => 'vehicleVRM',
                ],
                [
                    'title'    => 'Vehicle',
                    'sortBy' => 'makeModel',
                    'sub'    => [
                        [
                            'field'     => 'vehicleMake',
                        ],
                        [
                            'field'     => 'vehicleModel',
                            'formatter' => VehicleModelSubRow::class,
                        ],
                    ],
                ],
                [
                    'title'    => 'User/Site Id',
                    'sortBy' => 'tester',
                    'sub'    => [
                        [
                            'field'     => 'testUsername',
                        ],
                        [
                            'field'     => 'siteNumber',
                            'formatter' => SubRow::class,
                        ],
                    ],
                ],
                [
                    'title'    => 'Status/Type',
                    'sortBy' => 'statusType',
                    'sub'    => [
                        [
                            'field'     => 'status',
                        ],
                        [
                            'field'     => 'testType',
                            'formatter' => SubRow::class,
                        ],
                    ],
                ],
            ]
        );

        return $this;
    }

    private function defineFilterBuilder()
    {
        $this->filterBuilder = new FilterBuilder();
        $this->filterBuilder
            ->setOptions(
                [
                    'today'     => [
                        'label' => 'Today',
                        'from'  => strtotime('today'),
                        'to'    => strtotime('tomorrow -1 second')
                    ],
                    'lastWeek'  => [
                        'label' => 'Last week (Mon-Sun)',
                        'from'  => strtotime('monday this week - 7 days'),
                        'to'    => strtotime('monday this week - 1 second')
                    ],
                    'lastMonth' => [
                        'label' => 'Last Month (' . date('M', strtotime('last month')) . ')',
                        'from'  => strtotime('first day of last month midnight'),
                        'to'    => strtotime('first day of this month midnight -1 second')
                    ],
                ]
            );

        return $this;
    }

    /**
     * @param MotTestLogSummaryDto $logData
     *
     * @return MotTestLogViewModel
     */
    public function setMotTestLogSummary(MotTestLogSummaryDto $logData)
    {
        $this->logSummary = $logData;

        return $this;
    }

    /**
     * @return MotTestLogSummaryDto
     */
    public function getMotTestLogSummary()
    {
        return $this->logSummary;
    }

    /**
     * @param OrganisationDto $org
     *
     * @return MotTestLogViewModel
     */
    public function setOrganisation(OrganisationDto $org)
    {
        $this->organisation = $org;

        return $this;
    }

    /**
     * @return OrganisationDto
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @return MotTestLogFormViewModel
     */
    public function getFormModel()
    {
        return $this->formModel;
    }

    /**
     * @param MotTestLogFormViewModel $formModel
     *
     * @return MotTestLogViewModel
     */
    public function setFormModel($formModel)
    {
        $this->formModel = $formModel;

        return $this;
    }

    /**
     * @return $this
     */
    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @return Table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return $this
     */
    public function setFilterBuilder(FilterBuilder $filterBuilder)
    {
        $this->filterBuilder = $filterBuilder;

        return $this;
    }

    /**
     * @return FilterBuilder
     */
    public function getFilterBuilder()
    {
        return $this->filterBuilder;
    }
}
