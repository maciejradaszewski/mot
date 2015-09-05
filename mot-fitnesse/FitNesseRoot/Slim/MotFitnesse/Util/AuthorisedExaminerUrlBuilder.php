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

    const SITE = '/site[/:siteNumber]';
    const SITE_LINK = '/link[/:linkId]';
    const SITE_UNLINKED = '/authorised';

    protected $routesStructure
        = [
            self::AUTHORISED_EXAMINER =>
                [
                    self::SLOT => '',
                    self::AUTHORISED_EXAMINER_PRINCIPAL => '',
                    self::SITE                          => [
                        self::SITE_LINK   => '',
                    ],
                ],
        ];

    public static function of($id = null)
    {
        return new static($id);
    }

    /**
     * @param $id null by default
     *
     * @return AuthorisedExaminerUrlBuilder
     */
    public function __construct($id = null)
    {
        $this->appendRoutesAndParams(self::AUTHORISED_EXAMINER);

        if ($id !== null) {
            $this->routeParam('id', $id);
        }

        return $this;
    }

    public static function authorisedExaminer($organisationId = null)
    {
        return self::of($organisationId);
    }

    public function slot()
    {
        return $this->appendRoutesAndParams(self::SLOT);
    }

    public function authorisedExaminerPrincipal()
    {
        return $this->appendRoutesAndParams(self::AUTHORISED_EXAMINER_PRINCIPAL);
    }

    public static function site($aeId = null, $siteNr = null)
    {
        $url = self::of($aeId)
            ->appendRoutesAndParams(self::SITE);

        if (!empty($siteNr)) {
            $url->routeParam('siteNumber', $siteNr);
        }

        return $url;
    }

    public static function siteLink($aeId = null, $siteNumber = null, $linkId = null)
    {
        return self::site($aeId, $siteNumber)
            ->appendRoutesAndParams(self::SITE_LINK)
            ->routeParam('linkId', $linkId);
    }
}
