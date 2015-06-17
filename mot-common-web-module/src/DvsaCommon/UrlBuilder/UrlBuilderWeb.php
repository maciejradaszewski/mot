<?php

namespace DvsaCommon\UrlBuilder;

/**
 * Common url builder for web
 */
class UrlBuilderWeb extends AbstractUrlBuilder
{
    const REPLACE_CERT = '/replacement-certificate[/:id]';
    const REPLACE_CERT_SUMMARY = '/summary';
    const REPLACE_CERT_FINISH = '/finish/:motTestNumber';

    protected $routesStructure
        = [
            self::REPLACE_CERT => [
                self::REPLACE_CERT_SUMMARY => '',
                self::REPLACE_CERT_FINISH  => '',
            ],
        ];

    public static function replacementCertificate($id = null)
    {
        return self::of()->appendRoutesAndParams(self::REPLACE_CERT)
            ->routeParam('id', $id);
    }

    public static function replacementCertificateFinish($motTestNr)
    {
        return self::replacementCertificate()
            ->appendRoutesAndParams(self::REPLACE_CERT_FINISH)
            ->routeParam('motTestNumber', $motTestNr);
    }

    public static function replacementCertificateSummary($id)
    {
        return self::replacementCertificate($id)
            ->appendRoutesAndParams(self::REPLACE_CERT_SUMMARY);
    }
}
