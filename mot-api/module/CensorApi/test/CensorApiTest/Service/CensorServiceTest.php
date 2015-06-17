<?php

namespace CensorApiTest\Service;

use CensorApi\Service\CensorService;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\CensorPhraseRepository;

/**
 * Class CensorServiceTest
 * @package CensorApiTest\Service
 */
class CensorServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CensorService
     */
    private $censorService;

    /**
     * @var CensorPhraseRepository
     */
    private $censorPhraseRepositoryMock;

    public static function data()
    {
        return [
            [['text' => 'dirtyword', 'result' => true]]
            , [['text' => 'dirtywords', 'result' => true]]
            , [['text' => 'ddd1rtywords', 'result' => true]]
            , [['text' => 'ddd1rtyw0rds', 'result' => true]]
            , [['text' => 'dirts', 'result' => false]]
            , [['text' => 'word', 'result' => false]]
            , [['text' => 'notrelevant', 'result' => false]]
        ];
    }

    /**
     * @dataProvider data
     */
    public function testContainsProfanity($assumption)
    {
        $this->defineBadWords(['dirtyword']);
        $res = $this->censorService->containsProfanity($assumption['text']);
        $this->assertEquals($res, $assumption['result']);
    }

    private function defineBadWords(array $badWords)
    {
        $this->censorPhraseRepositoryMock->expects($this->any())
            ->method('getBlacklist')
            ->with()
            ->will($this->returnValue($badWords));
    }

    protected function setUp()
    {
        $this->censorPhraseRepositoryMock = XMock::of(CensorPhraseRepository::class);
        $this->censorService = new CensorService($this->censorPhraseRepositoryMock);
        parent::setUp();
    }
}
