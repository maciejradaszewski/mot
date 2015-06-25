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
    const MOT_TEST_LOG = '/mot-test-log';
    const MOT_TEST_LOG_CSV = '/csv';

    protected $routesStructure
        = [
            self::REPLACE_CERT => [
                self::REPLACE_CERT_SUMMARY => '',
                self::REPLACE_CERT_FINISH  => '',
            ],
            self::MOT_TEST_LOG => [
                self::MOT_TEST_LOG_CSV => '',
            ],
        ];

    public static function motTestLogs()
    {
        return self::of()->appendRoutesAndParams(self::MOT_TEST_LOG);
    }

    public static function motTestLogDownloadCsv()
    {
        return self::motTestLogs()->appendRoutesAndParams(self::MOT_TEST_LOG_CSV);
    }

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
