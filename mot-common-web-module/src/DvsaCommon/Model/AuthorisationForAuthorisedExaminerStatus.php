<?php


namespace DvsaCommon\Model;


use DvsaCommon\Enum\AuthorisationForAuthorisedExaminerStatusCode;

class AuthorisationForAuthorisedExaminerStatus
{
    public static function getPossibleAuthForAuthorisedExaminerStatuses()
    {
        return [
            AuthorisationForAuthorisedExaminerStatusCode::APPLIED,
            AuthorisationForAuthorisedExaminerStatusCode::APPROVED,
            AuthorisationForAuthorisedExaminerStatusCode::LAPSED,
            AuthorisationForAuthorisedExaminerStatusCode::REJECTED,
            AuthorisationForAuthorisedExaminerStatusCode::RETRACTED,
            AuthorisationForAuthorisedExaminerStatusCode::SURRENDERED,
            AuthorisationForAuthorisedExaminerStatusCode::WITHDRAWN,
        ];
    }
}