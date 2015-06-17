<?php

namespace Organisation\ViewModel\MotTestLog;

use DvsaClient\ViewModel\DateViewModel;
use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Dto\Organisation\MotTestLogSummaryDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;

class MotTestLogViewModel
{
    /** @var MotTestLogSummaryDto */
    private $logSummary;
    /** @var OrganisationDto */
    private $organisation;
    /** @var MotTestLogFormViewModel */
    private $formModel;
    /** @var  array array of extracted mot tests prepared for list */
    private $tests;


    public function __construct(
        OrganisationDto $org,
        MotTestLogSummaryDto $logData,
        MotTestLogFormViewModel $formModel
    ) {
        $this->setOrganisation($org);
        $this->setMotTestLogSummary($logData);
        $this->setFormModel($formModel);

        $this->setDefaultValues();
    }

    private function setDefaultValues()
    {
        $today = DateUtils::today();

        $formModel = $this->getFormModel();

        if ($formModel->getDateFrom()->getDate() === null && $formModel->getDateTo()->getDate() === null) {
            $formModel
                ->setDateTo(
                    (new DateViewModel())->setDate($today)
                )
                ->setDateFrom(
                    (new DateViewModel())->setDate(DateUtils::subtractCalendarMonths($today, 1))
                );
        }
    }

    public function getDownloadUrl()
    {
        return AuthorisedExaminerUrlBuilderWeb::motTestLogDownloadCsv($this->organisation->getId())
            ->queryParams(
                [
                    MotTestLogFormViewModel::FLD_DATE_FROM => DateTimeApiFormat::date(
                        $this->formModel->getDateFrom()->getDate()
                    ),
                    MotTestLogFormViewModel::FLD_DATE_TO   => DateTimeApiFormat::date(
                        $this->formModel->getDateTo()->getDate()
                    ),
                ]
            )->toString();
    }


    /**
     * @param MotTestLogSummaryDto $logData
     *
     * return MotTestLogViewModel
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
     * return MotTestLogViewModel
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
     * return MotTestLogViewModel
     */
    public function setFormModel($formModel)
    {
        $this->formModel = $formModel;

        return $this;
    }


    public function getTests()
    {
        return $this->tests;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setTests($data)
    {
        $this->tests = $data;

        return $this;
    }
}
