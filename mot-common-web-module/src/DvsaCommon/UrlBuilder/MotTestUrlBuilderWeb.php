<?php

namespace DvsaCommon\UrlBuilder;

/**
 * Url builder for mot test.
 *
 * @deprecated Use the route name directly instead, while using the URL generator helper.
 */
class MotTestUrlBuilderWeb extends AbstractUrlBuilder
{
    const MOTTEST = '/mot-test[/:motTestNumber]';
    const SUMMARY = '/test-summary';
    const PRINT_CERT = '/print-certificate';
    const PRINT_CERT_DUPL = '/print-duplicate-certificate';
    const SHOW_RESULT = '/test-result';
    const PRINT_DUPLICATE_RESULT = '/print-duplicate-test-result';
    const ABORT_SUCCESS = '/abort-success';
    const ABORT_FAIL = '/abort-fail';
    const CANCEL = '/cancel';
    const CANCELLED = '/cancelled';
    const OPTIONS = '/options';

    const REFUSE = '/refuse-to-test/:testTypeCode[/:id]';
    const REFUSE_REASON = '/reason';
    const REFUSE_SUMMARY = '/summary';
    const REFUSE_PRINT = '/print';

    protected $routesStructure
        = [
            self::MOTTEST => [
                self::PRINT_CERT             => '',
                self::PRINT_CERT_DUPL        => '',
                self::SHOW_RESULT            => '',
                self::PRINT_DUPLICATE_RESULT => '',
                self::SUMMARY                => '',
                self::ABORT_SUCCESS          => '',
                self::ABORT_FAIL             => '',
                self::CANCEL                 => '',
                self::CANCELLED              => '',
                self::OPTIONS                => '',
            ],
            self::REFUSE  => [
                self::REFUSE_REASON  => '',
                self::REFUSE_SUMMARY => '',
                self::REFUSE_PRINT   => '',
            ],
        ];

    /**
     * @return $this
     */
    public static function motTest($motTestNr = null)
    {
        $url = self::of()->appendRoutesAndParams(self::MOTTEST);

        if ($motTestNr !== null) {
            $url->routeParam('motTestNumber', $motTestNr);
        }

        return $url;
    }

    public static function showResult($motTestNr)
    {
        return self::motTest($motTestNr)->appendRoutesAndParams(self::SHOW_RESULT);
    }

    public static function printDuplicateResult($motTestNr)
    {
        return self::motTest($motTestNr)->appendRoutesAndParams(self::PRINT_DUPLICATE_RESULT);
    }

    public static function summary($motTestNr)
    {
        return self::motTest($motTestNr)->appendRoutesAndParams(self::SUMMARY);
    }

    public static function abortSuccess($motTestNr)
    {
        return self::motTest($motTestNr)->appendRoutesAndParams(self::ABORT_SUCCESS);
    }

    public static function abortFail($motTestNr)
    {
        return self::motTest($motTestNr)->appendRoutesAndParams(self::ABORT_FAIL);
    }

    public static function cancel($motTestNr)
    {
        return self::motTest($motTestNr)->appendRoutesAndParams(self::CANCEL);
    }

    public static function cancelled($motTestNr)
    {
        return self::motTest($motTestNr)->appendRoutesAndParams(self::CANCELLED);
    }

    public static function printCertificate($motTestNr)
    {
        return self::motTest($motTestNr)->appendRoutesAndParams(self::PRINT_CERT);
    }

    public static function printCertificateDuplicate($motTestNr)
    {
        return self::motTest($motTestNr)->appendRoutesAndParams(self::PRINT_CERT_DUPL);
    }

    public static function options($motTestNr)
    {
        return self::motTest($motTestNr)->appendRoutesAndParams(self::OPTIONS);
    }


    private static function refuse($testTypeCode, $vehicleId = null)
    {
        $url = self::of()->appendRoutesAndParams(self::REFUSE)
            ->routeParam('testTypeCode', $testTypeCode);

        if ($vehicleId !== null) {
            $url->routeParam('id', $vehicleId);
        }

        return $url;
    }

    public static function refuseReason($testTypeCode, $vehicleId)
    {
        return self::refuse($testTypeCode, $vehicleId)
            ->appendRoutesAndParams(self::REFUSE_REASON);
    }

    public static function refuseSummary($testTypeCode, $vehicleId)
    {
        return self::refuse($testTypeCode, $vehicleId)
            ->appendRoutesAndParams(self::REFUSE_SUMMARY);
    }

    public static function refusePrint($testTypeCode, $vehicleId)
    {
        return self::refuse($testTypeCode, $vehicleId)
            ->appendRoutesAndParams(self::REFUSE_PRINT);
    }
}
