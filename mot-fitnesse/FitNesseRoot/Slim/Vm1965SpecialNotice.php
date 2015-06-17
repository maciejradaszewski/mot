<?php

require_once 'configure_autoload.php';
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm1965SpecialNotice {

    const NOT_APPLICABLE = 'NOT APPLICABLE';

    private $username;
    private $password = TestShared::PASSWORD;
    private $personId;
    private $issueNumber;

    private $_specialNoticeCache;
    private $isNoticePresent;

    private $futureIssueNumber;
    private $expiredIssueNumber;

    public function reset()
    {
        $this->isNoticePresent = null;
        $this->_specialNoticeCache = null;
    }

    public function setUser($username)
    {
        $this->username = $username;
    }

    public function setPersonId($personId)
    {
        $this->personId = $personId;
    }

    public function setIssueNumber($issueNumber)
    {
        $this->issueNumber = $issueNumber;
    }

    protected function getSpecialNotice()
    {
        if ($this->isNoticePresent === NULL) {
            $curlHandle = curl_init(
                (new UrlBuilder())->specialNotice()->routeParam('id', $this->personId)->toString()
            );
            TestShared::SetupCurlOptions($curlHandle);
            TestShared::setAuthorizationInHeaderForUser($this->username, $this->password, $curlHandle);
            $result = TestShared::execCurlForJson($curlHandle);
            $specialNotices = $result['data'];

            foreach($specialNotices as $specialNotice) {
                if ($specialNotice['content']['issueNumber'] == $this->issueNumber) {
                    $this->_specialNoticeCache = $specialNotice;
                    $this->isNoticePresent = true;
                    break;
                }
            }

            if ($this->isNoticePresent === NULL) {
                $this->isNoticePresent = false;
            }
        }
        return $this->_specialNoticeCache;
    }

    public function isPresent()
    {
        $this->getSpecialNotice();
        return $this->isNoticePresent ? 'YES' : 'NO';
    }

    public function title()
    {
        $specialNotice = $this->getSpecialNotice();
        if ($specialNotice == NULL) return self::NOT_APPLICABLE;

        return $specialNotice['content']['title'];
    }

    public function isAcknowledged()
    {
        $specialNotice = $this->getSpecialNotice();
        if ($specialNotice == NULL) return self::NOT_APPLICABLE;

        return $specialNotice['isAcknowledged'] ? 'YES' : 'NO';
    }

    public function isExpired()
    {
        $specialNotice = $this->getSpecialNotice();
        if ($specialNotice == NULL) return self::NOT_APPLICABLE;

        return $specialNotice['isExpired'] ? 'YES' : 'NO';
    }

    public function generateNotices()
    {
        $this->expiredIssueNumber = $this->createSpecialNotice(
            '1989-02-10', 'Expired special notice'
        );

        $this->futureIssueNumber = $this->createSpecialNotice(
            '2021-01-17', 'Future special notice'
        );
    }

    public function futureNoticeIssueNumber()
    {
        return $this->futureIssueNumber;
    }

    public function expiredNoticeIssueNumber()
    {
        return $this->expiredIssueNumber;
    }

    /**
     * @param string $publishDate
     * @param string $title
     */
    private function createSpecialNotice($publishDate, $title)
    {
        $testSupportHelper = new TestSupportHelper();

        $response = $testSupportHelper->createSpecialNotice($publishDate, true, $title);

        $testSupportHelper->broadcastSpecialNotice('ft-cata', $response['id'], 'false');

        $testSupportHelper->broadcastSpecialNotice('ft-catb', $response['id'], 'true');

        return $response['issueNumber'];
    }
}
