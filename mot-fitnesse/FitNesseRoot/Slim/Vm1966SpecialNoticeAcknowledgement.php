<?php

require_once 'configure_autoload.php';
use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Date\DateUtils;
use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Vm1966SpecialNoticeAcknowledgement
{

    const NOT_APPLICABLE = 'NOT APPLICABLE';

    private $username;
    private $password;
    private $personId;
    private $specialNoticeId;
    private $isAcknowledged;

    public function setWasAcknowledged($wasAcknowledged)
    {
        //Column is informative only
    }

    public function setSetAcknowledged($isAcknowledged)
    {
        $this->isAcknowledged = $isAcknowledged == 'YES';
    }

    public function result()
    {
        $jsonResult = $this->doQuery();

        if (array_key_exists('errors', $jsonResult)) {
            return 'FAILURE';
        }

        return 'SUCCESS';
    }

    protected function doQuery()
    {
        $curlHandle = TestShared::prepareCurlHandleToSendJsonWithCreds(
            (new UrlBuilder())->specialNotice()
                ->routeParam('id', $this->personId)
                ->routeParam('snId', $this->specialNoticeId)
                ->toString(),
            TestShared::METHOD_POST,
            ['isAcknowledged' => $this->isAcknowledged],
            new CredentialsProvider($this->username, $this->password)
        );

        return TestShared::execCurlForJson($curlHandle);
    }

    public function beginTable()
    {
        $testSupportHelper = new TestSupportHelper();

        $schememgt = $testSupportHelper->createSchemeManager();
        $tester = $testSupportHelper->createTester($schememgt['username'], [2004]);
        $this->username = $tester['username'];
        $this->password = $tester['password'];
        $this->personId = $tester['personId'];

        $response = $testSupportHelper->createSpecialNotice(DateTimeApiFormat::date(DateUtils::today()), true, 'title');

        $this->specialNoticeId = $testSupportHelper->broadcastSpecialNotice($this->username, $response['id'], false);
    }
}
