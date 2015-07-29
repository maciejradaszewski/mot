<?php

namespace Organisation\Form;

use DvsaClient\ViewModel\AbstractFormModel;
use DvsaCommon\Dto\Common\AuthForAeStatusDto;
use DvsaCommon\Dto\Organisation\AuthorisedExaminerAuthorisationDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\AuthorisationForAuthorisedExaminerStatusCode;
use Zend\Stdlib\Parameters;

class AeStatusForm extends AbstractFormModel
{
    const FIELD_STATUS = 'status';

    /**
     * @var string
     */
    private $formUrl;
    /**
     * @var string
     */
    private $status;

    public function fromPost(Parameters $data)
    {
        $this->setStatus($data->get(self::FIELD_STATUS));

        return $this;
    }

    public function toDto()
    {
        // Set the status
        $status = (new AuthForAeStatusDto())
            ->setCode($this->getStatus());

        // Set the authorisation
        $auth = (new AuthorisedExaminerAuthorisationDto())
            ->setStatus($status);

        // Attached it to the organisation
        $dto = (new OrganisationDto())
            ->setAuthorisedExaminerAuthorisation($auth);

        return $dto;
    }

    public function addErrorsFromApi($errors)
    {
        $this->addErrors($errors);
    }

    /**
     * @return string
     */
    public function getFormUrl()
    {
        return $this->formUrl;
    }

    /**
     * @param string $formUrl
     * @return $this
     */
    public function setFormUrl($formUrl)
    {
        $this->formUrl = $formUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return [
            AuthorisationForAuthorisedExaminerStatusCode::APPLIED => 'Applied',
            AuthorisationForAuthorisedExaminerStatusCode::APPROVED => 'Approved',
            AuthorisationForAuthorisedExaminerStatusCode::LAPSED => 'Lapsed',
            AuthorisationForAuthorisedExaminerStatusCode::REJECTED => 'Rejected',
            AuthorisationForAuthorisedExaminerStatusCode::RETRACTED => 'Retracted',
            AuthorisationForAuthorisedExaminerStatusCode::SURRENDERED => 'Surrendered',
            AuthorisationForAuthorisedExaminerStatusCode::WITHDRAWN => 'Withdrawn',
        ];
    }
}
