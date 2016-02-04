<?php


namespace DvsaCommon\Model;


use DvsaCommon\Enum\CompanyTypeCode;

class CompanyType
{
    public static function getPossibleCompanyTypes()
    {
        return [
            CompanyTypeCode::PARTNERSHIP,
            CompanyTypeCode::PUBLIC_BODY,
            CompanyTypeCode::SOLE_TRADER,
            CompanyTypeCode::COMPANY,
        ];
    }
}