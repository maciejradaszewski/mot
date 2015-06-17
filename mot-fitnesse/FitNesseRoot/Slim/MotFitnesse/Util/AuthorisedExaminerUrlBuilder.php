<?php

namespace MotFitnesse\Util;

/**
 * I'm building my professional career on comments
 */
class AuthorisedExaminerUrlBuilder extends AbstractUrlBuilder
{
    const AUTHORISED_EXAMINER = '/authorised-examiner[/:id]';
    const SLOT = '/slot';
    const AUTHORISED_EXAMINER_PRINCIPAL = '/authorised-examiner-principal[/:principalId]';

    protected $routesStructure
        = [
            self::AUTHORISED_EXAMINER =>
                [
                    self::SLOT => '',
                    self::AUTHORISED_EXAMINER_PRINCIPAL => '',
                ],
        ];

    public static function authorisedExaminer($organisationId = null)
    {
        $urlBuilder = new AuthorisedExaminerUrlBuilder();

        $urlBuilder->appendRoutesAndParams(self::AUTHORISED_EXAMINER);

        if (null !== $organisationId) {
            $urlBuilder->routeParam('id', $organisationId);
        }

        return $urlBuilder;
    }

    public function slot()
    {
        return $this->appendRoutesAndParams(self::SLOT);
    }

    public function authorisedExaminerPrincipal()
    {
        return $this->appendRoutesAndParams(self::AUTHORISED_EXAMINER_PRINCIPAL);
    }
}
