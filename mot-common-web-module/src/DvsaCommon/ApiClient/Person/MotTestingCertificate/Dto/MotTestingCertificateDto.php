<?php

namespace DvsaCommon\ApiClient\Person\MotTestingCertificate\Dto;

use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class MotTestingCertificateDto implements ReflectiveDtoInterface
{
    private $id;
    private $siteNumber;
    private $vehicleClassGroupCode;
    private $certificateNumber;
    private $dateOfQualification;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return MotTestingCertificateDto
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getSiteNumber()
    {
        return $this->siteNumber;
    }

    /**
     * @param int $siteNumber
     * @return MotTestingCertificateDto
     */
    public function setSiteNumber($siteNumber)
    {
        $this->siteNumber = $siteNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getVehicleClassGroupCode()
    {
        return $this->vehicleClassGroupCode;
    }

    /**
     * @param string $vehicleClassGroupCode
     * @return MotTestingCertificateDto
     */
    public function setVehicleClassGroupCode($vehicleClassGroupCode)
    {
        $this->vehicleClassGroupCode = $vehicleClassGroupCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCertificateNumber()
    {
        return $this->certificateNumber;
    }

    /**
     * @param string $certificateNumber
     * @return MotTestingCertificateDto
     */
    public function setCertificateNumber($certificateNumber)
    {
        $this->certificateNumber = $certificateNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getDateOfQualification()
    {
        return $this->dateOfQualification;
    }

    /**
     * @param string $dateOfQualification
     * @return MotTestingCertificateDto
     */
    public function setDateOfQualification($dateOfQualification)
    {
        $this->dateOfQualification = $dateOfQualification;
        return $this;
    }
}
