<?php

namespace DvsaCommon\UrlBuilder;

/**
 * I'm happy to provide this very descriptive comment.
 *
 * Url builder for authorised examiner if You didn't guess that by the name of class.
 */
class AuthorisedExaminerUrlBuilder extends AbstractUrlBuilder
{
    const AUTHORISED_EXAMINER = 'authorised-examiner[/:id]';
    const STATUS = '/status';

    const SLOT = '/slot';
    const SLOT_USAGE = '/slot-usage[/:period]';

    const AUTHORISED_EXAMINER_PRINCIPAL = '/authorised-examiner-principal[/:principalId]';
    const AUTHORISED_EXAMINER_LIST = '/list';

    const MOT_TEST_LOG = '/mot-test-log';
    const MOT_TEST_LOG_SUMMARY = '/summary';

    const AUTHORISED_EXAMINER_NUMBER = '/number[/:number]';

    protected $routesStructure
        = [
            self::AUTHORISED_EXAMINER =>
                [
                    self::STATUS => '',
                    self::SLOT => '',
                    self::SLOT_USAGE => '',
                    self::AUTHORISED_EXAMINER_PRINCIPAL => '',
                    self::AUTHORISED_EXAMINER_LIST => '',
                    self::MOT_TEST_LOG => [
                        self::MOT_TEST_LOG_SUMMARY => '',
                    ],
                    self::AUTHORISED_EXAMINER_NUMBER => '',
                ],
        ];

    /**
     * @param $id null by default
     *
     * @return static
     */
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

    public function slot()
    {
        return $this->appendRoutesAndParams(self::SLOT);
    }

    public function slotUsage()
    {
        return $this->appendRoutesAndParams(self::SLOT_USAGE);
    }

    public function authorisedExaminerPrincipal()
    {
        return $this->appendRoutesAndParams(self::AUTHORISED_EXAMINER_PRINCIPAL);
    }

    public function authorisedExaminerList()
    {
        return $this->appendRoutesAndParams(self::AUTHORISED_EXAMINER_LIST);
    }

    /**
     * @param int $orgId
     *
     * @return AuthorisedExaminerUrlBuilder
     */
    public static function motTestLog($orgId)
    {
        return (new static($orgId))->appendRoutesAndParams(self::MOT_TEST_LOG);
    }

    /**
     * @param int $orgId
     *
     * @return AuthorisedExaminerUrlBuilder
     */
    public static function motTestLogSummary($orgId)
    {
        return self::motTestLog($orgId)->appendRoutesAndParams(self::MOT_TEST_LOG_SUMMARY);
    }

    /**
     * @param $number null by default
     *
     * @return $this
     */
    public function authorisedExaminerByNumber($number = null)
    {
         $this->appendRoutesAndParams(self::AUTHORISED_EXAMINER_NUMBER);

        if ($number !== null) {
            $this->routeParam('number', $number);
        }
        return $this;
    }

    public static function status($id)
    {
        return self::of($id)->appendRoutesAndParams(self::STATUS);
    }
}
