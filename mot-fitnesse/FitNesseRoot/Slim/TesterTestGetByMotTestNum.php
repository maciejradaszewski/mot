<?php
require_once 'configure_autoload.php';

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class TesterTestGetByMotTestNum
{
    public $username = TestShared::USERNAME_ENFORCEMENT;
    public $password = TestShared::PASSWORD;

    private $motTestNumber;
    protected $searchResult;

    public function __construct($id)
    {
        $this->motTestNumber = $id;
    }

    protected function fetchSearchResult()
    {
        if ($this->searchResult == null) {
            $this->searchResult = Testshared::execCurlForJsonFromUrlBuilder(
                $this,
                (new UrlBuilder())->tester()->queryParam('certificateNumber', $this->motTestNumber)
            );
        }
        return $this->searchResult;
    }

    public function query()
    {
        $result = $this->fetchSearchResult();

        $queryData = [];

        if (isset($result['error'])) {
            $queryData[] = [
                ['message', $result['error']],
                ['content', $result['content']['message']],
            ];

        } elseif (isset($result['data'])) {
            $resultData = $result['data'];

            $user = $resultData['user'];
            $vtsSites = $resultData['vtsSites'];

            $result = [
                ['id', $resultData['id']],

                ['user.username', $user['username']],
                ['user.password', 'password'],
                ['user.displayRole', null],
                ['user.displayName', $user['displayName']],

                ['roles', json_encode($resultData['roles'])],
            ];

            if (isset($vtsSites[0])) {
                $result = array_merge(
                    $result,
                    [
                        ['vtsSites.0.id', $vtsSites[0]['id']],
                        ['vtsSites.0.name', $vtsSites[0]['name']],
                        ['vtsSites.0.siteNumber', $vtsSites[0]['siteNumber']],
                        ['vtsSites.0.address', $vtsSites[0]['address']],
                        ['vtsSites.0.positions', json_encode($vtsSites[0]['positions'])],
                        ['vtsSites.0.lastSiteAssessment', $vtsSites[0]['lastSiteAssessment']],
                    ]
                );
            } else {
                $result[] = ['vtsSites', json_encode($vtsSites)];
            }

            $queryData[] = $result;
        }

        return $queryData;
    }
}
