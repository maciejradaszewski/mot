<?php

require_once 'configure_autoload.php';
use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Date\DateUtils;
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm2279ViewSpecialNoticesNonTester {

    const YES = 'Yes';
    const NO = 'No';

    private $username;
    private $password = TestShared::PASSWORD;
    private $specialNotices = [];

    private $createRoles = [
        TestShared::USERNAME_SCHEMEUSER
    ];

    /**
     * @param mixed $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    private function getSpecialNotices()
    {
        $urlBuilder = (new UrlBuilder())->specialNoticeContent();

        if (in_array($this->username, $this->createRoles)) {
            $urlBuilder = $urlBuilder->queryParams(['listAll' => 'true']);
        }
        $curlHandle = curl_init($urlBuilder->toString());

        TestShared::SetupCurlOptions($curlHandle);
        TestShared::setAuthorizationInHeaderForUser($this->username, $this->password, $curlHandle);

        $result = TestShared::execCurlForJson($curlHandle);
        $this->specialNotices = $result['data'];
    }

    public function allExternallyPublished()
    {
        $this->getSpecialNotices();
        foreach ($this->specialNotices as $specialNotice) {
            $externalPublishDate = new DateTime($specialNotice['externalPublishDate']);
            $today = new DateTime("now");

            if ($externalPublishDate > $today) {
                return self::NO;
            }
        }
        return self::YES;
    }

    public function includesDraftNotices()
    {
        foreach ($this->specialNotices as $specialNotice) {
           if (!$specialNotice['isPublished']) {
            return self::YES;
           }
        }
        return self::NO;
    }

    public function setInformationOnResult() {}

    public function beginTable()
    {
        $now = new DateTime();
        $this->createSpecialNotice(
            $now->format("Y-m-d"), 'Currently Active', true
        );
        $this->createSpecialNotice(
            $now->format("Y-m-d"), 'Draft', false
        );
        $this->createSpecialNotice(
            DateTimeApiFormat::date(DateUtils::today()->add(new DateInterval('P1Y'))), 'Future published', true
        );
    }

    /**
     * @param string $publishDate
     * @param string $title
     * @param boolean $isPublished
     */
    private function createSpecialNotice($publishDate, $title, $isPublished)
    {
        $testSupportHelper = new TestSupportHelper();

        $testSupportHelper->createSpecialNotice($publishDate, $isPublished, $title);
    }
}
