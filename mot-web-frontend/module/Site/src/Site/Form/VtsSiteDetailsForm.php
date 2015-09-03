<?php

namespace Site\Form;

use DvsaClient\ViewModel\AbstractFormModel;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use Zend\Stdlib\Parameters;

class VtsSiteDetailsForm extends AbstractFormModel
{
    const FIELD_VEHICLE_CLASS = 'classes';
    const FIELD_NAME = 'name';
    const FIELD_STATUS = 'status';
    const FIELD_AREA_OFFICE = 'area_office';

    private $name;
    private $classes;
    private $status;
    private $areaOffice;
    /** @var VehicleTestingStationDto $vtsDto */
    private $vtsDto;
    private $formUrl;


    public function fromPost(Parameters $data)
    {
        $this->clearEmptyParams($data);

        $this->setName($data->get(self::FIELD_NAME))
            ->setClasses($data->get(self::FIELD_VEHICLE_CLASS))
            ->setStatus($data->get(self::FIELD_STATUS))
        ;

        return $this;
    }

    public function fromDto(VehicleTestingStationDto $vtsDto)
    {
        $this->vtsDto = $vtsDto;

        $this
            ->setName($vtsDto->getName())
            ->setClasses($vtsDto->getTestClasses())
            ->setStatus($vtsDto->getStatus())
        ;

        return $this;
    }

    public function toDto()
    {
        $siteDto = new VehicleTestingStationDto();
        $siteDto
            ->setName($this->getName())
            ->setTestClasses($this->getClasses())
            ->setStatus($this->getStatus())
        ;

        return $siteDto;
    }

    public function addErrorsFromApi($errors)
    {
        $this->addErrors($errors);
    }

    /**
     * @param int $class
     * @return bool
     */
    public function isClassChecked($class)
    {
        if (!empty($this->classes)) {
            return in_array($class, $this->classes);
        }
        return false;
    }

    public function getTestingFacilities()
    {
        return [
            0, 1, 2, 3, 4, '5 or more',
        ];
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @param mixed $classes
     * @return $this
     */
    public function setClasses($classes)
    {
        $this->classes = $classes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAreaOffice()
    {
        return $this->areaOffice;
    }

    /**
     * @param mixed $areaOffice
     * @return $this
     */
    public function setAreaOffice($areaOffice)
    {
        $this->areaOffice = $areaOffice;
        return $this;
    }

    /**
     * @param $formUrl
     * @return $this
     */
    public function setFormUrl($formUrl)
    {
        $this->formUrl = $formUrl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFormUrl()
    {
        return $this->formUrl;
    }

    /**
     * @return VehicleTestingStationDto
     */
    public function getVtsDto()
    {
        return $this->vtsDto;
    }

    /**
     * @param VehicleTestingStationDto $vtsDto
     * @return $this
     */
    public function setVtsDto($vtsDto)
    {
        $this->vtsDto = $vtsDto;
        return $this;
    }

    public function getStatuses()
    {
        return [
            'AV' => 'Approved',
            'AP' =>'Applied',
            'RE' =>'Retracted',
            'RJ' =>'Rejected',
            'LA' => 'Lapsed',
            'EX' =>'Extinct',
        ];
    }

    public function getStatusName()
    {
        $statusCode = $this->getStatus();
        $statuses = $this->getStatuses();

        return $statuses[$statusCode];
    }

}