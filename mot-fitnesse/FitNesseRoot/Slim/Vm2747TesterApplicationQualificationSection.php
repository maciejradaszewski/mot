<?php

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Vm2747TesterApplicationQualificationSection extends Vm820AndVm821And823Base
{
    private $examiningBody;
    private $qualification;
    private $certificateNumber;
    private $dateAwarded;
    private $country;

    /**
     * @param mixed $certiticateNumber
     *
     * @return $this
     */
    public function setCertificateNumber($certiticateNumber)
    {
        $this->certificateNumber = $certiticateNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCertificateNumber()
    {
        return $this->certificateNumber;
    }

    /**
     * @param mixed $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $dateAwarded
     *
     * @return $this
     */
    public function setDateAwarded($dateAwarded)
    {
        $this->dateAwarded = $dateAwarded;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateAwarded()
    {
        return $this->dateAwarded;
    }

    /**
     * @param mixed $examiningBody
     *
     * @return $this
     */
    public function setExaminingBody($examiningBody)
    {
        $this->examiningBody = $examiningBody;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExaminingBody()
    {
        return $this->examiningBody;
    }

    /**
     * @param mixed $qualification
     *
     * @return $this
     */
    public function setQualification($qualification)
    {
        $this->qualification = $qualification;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQualification()
    {
        return $this->qualification;
    }


    protected function mainTestFunction($uuid)
    {
        $qualification = $this->input();

        $urlBuilder = (new UrlBuilder())
            ->testerApplication()->routeParam('uuid', $uuid)->testerApplicationQualification();

        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            $urlBuilder->toString(),
            TestShared::METHOD_POST,
            $qualification
        );

        $this->result = TestShared::execCurlForJson($curlHandle);

        return TestShared::resultIsSuccess($this->result);
    }

    public function errorMessages()
    {
        return TestShared::errorMessages($this->result);
    }

    public function savedCorrectly()
    {
        $expected = $this->input();
        $filter = ['certificateNumber', 'dateAwarded', 'qualification'];

        if (isset($expected['country'])) {
            $filter[] = 'country';
        }
        return (new \MotFitnesse\Licensing\Tester\TesterApplicationHelper($this->newApplicationUuid))->savedCorrectly(
            $expected,
            $this->result,
            function ($data) {
                return $data['qualifications'][count($data['qualifications']) - 1];
            },
            $filter
        );
    }

    /**
     * @return array
     */
    protected function input()
    {
        $input = [
            'qualification'     => $this->getQualification(),
            'certificateNumber' => $this->getCertificateNumber(),
            'dateAwarded'       => $this->getDateAwarded(),
        ];

        if ($this->getCountry()) {
            $input['country'] = $this->getCountry();
        }

        return $input;
    }
}