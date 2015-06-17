<?php

namespace DvsaCommon\UrlBuilder;

class AuthorisedExaminerDesignatedManagerUrlBuilder extends UrlBuilder
{
    const AUTHORISED_EXAMINER_DESIGNATED_MANAGER = 'authorised-examiner-designated-manager-application[/:uuid]';
    const APPLICANT_DETAILS = '/applicant-details';
    const DECLARATION = '/declaration';
    const CONVICTIONS = '/convictions';
    const STATUS = '/status';

    protected $routesStructure
        = [
            self::AUTHORISED_EXAMINER_DESIGNATED_MANAGER =>
                [
                    self::APPLICANT_DETAILS => '',
                    self::DECLARATION       => '',
                    self::CONVICTIONS       => '',
                    self::STATUS            => '',
                ],
        ];

    public static function authorisedExaminerDesignatedManagerApplication()
    {
        $urlBuilder = new self();

        return $urlBuilder->appendRoutesAndParams(self::AUTHORISED_EXAMINER_DESIGNATED_MANAGER);
    }

    public function applicantDetails()
    {
        return $this->appendRoutesAndParams(self::APPLICANT_DETAILS);
    }

    public function declaration()
    {
        return $this->appendRoutesAndParams(self::DECLARATION);
    }

    public function convictions()
    {
        return $this->appendRoutesAndParams(self::CONVICTIONS);
    }

    public function status()
    {
        return $this->appendRoutesAndParams(self::STATUS);
    }
}
