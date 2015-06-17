<?php

namespace DvsaCommon\UrlBuilder;

/**
 * Url Builder for API for Reports
 */
class ReportUrlBuilder extends AbstractUrlBuilder
{
    const REPORT_NAME = 'get-report-name/:id';
    const CREATE_DOCUMENT = 'create-document';
    const DELETE_DOCUMENT = 'delete-document/:id';
    const PRINT_REPORT = 'print-report/:docId';
    const PRINT_CERTIFICATE = 'certificate-print/:id[/:dupmode]';
    const PRINT_CONTINGENCY_CERTIFICATE = 'contingency-print/:name';

    protected $routesStructure
        = [
            self::REPORT_NAME                   => '',
            self::CREATE_DOCUMENT               => '',
            self::DELETE_DOCUMENT               => '',
            self::PRINT_REPORT                  => '',
            self::PRINT_CERTIFICATE             => '',
            self::PRINT_CONTINGENCY_CERTIFICATE => '',
        ];

    public static function of()
    {
        return new static();
    }

    public static function reportName($id = null)
    {
        return self::of()
            ->appendRoutesAndParams(self::REPORT_NAME)
            ->routeParam('id', $id);
    }

    public static function createDocument()
    {
        return self::of()->appendRoutesAndParams(self::CREATE_DOCUMENT);
    }

    public static function deleteDocument($id = null)
    {
        return self::of()
            ->appendRoutesAndParams(self::DELETE_DOCUMENT)
            ->routeParam('id', $id);
    }

    public static function printReport($docId)
    {
        return self::of()
            ->appendRoutesAndParams(self::PRINT_REPORT)
            ->routeParam('docId', $docId);
    }

    public static function printCertificate($motTestNr, $duplicateMode = null)
    {
        $url = self::of()
            ->appendRoutesAndParams(self::PRINT_CERTIFICATE)
            ->routeParam('id', $motTestNr);

        if ($duplicateMode !== null) {
            $url->routeParam('dupmode', $duplicateMode);
        }

        return $url;
    }

    public static function printContingencyCertificate($name = null)
    {
        $url = self::of()->appendRoutesAndParams(self::PRINT_CONTINGENCY_CERTIFICATE);

        if ($name !== null) {
            $url->routeParam('name', $name);
        }

        return $url;
    }
}
