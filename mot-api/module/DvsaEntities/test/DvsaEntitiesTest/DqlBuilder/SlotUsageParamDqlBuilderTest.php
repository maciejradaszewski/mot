<?php

namespace DvsaEntitiesTest\DqlBuilder;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaEntities\DqlBuilder\SearchParam\OrgSlotUsageParam;
use DvsaEntities\DqlBuilder\SlotUsageParamDqlBuilder;

class SlotUsageParamDqlBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SlotUsageParamDqlBuilder
     */
    private $dqlBuilder;

    public function setUp()
    {
        $doctrineConfigMock = $this->getMockBuilder(\Doctrine\ORM\Configuration::class)
            ->disableOriginalConstructor()
            ->setMethods(['getDefaultQueryHints', 'isSecondLevelCacheEnabled'])
            ->getMock();

        $doctrineConfigMock->expects($this->any())
            ->method('isSecondLevelCacheEnabled')
            ->will($this->returnValue(false));

        $emMock = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        $emMock->expects($this->any())->method('getConfiguration')->willReturn($doctrineConfigMock);

        $emMock->expects($this->any())
            ->method('createQuery')
            ->will($this->returnValue(new \Doctrine\ORM\Query($emMock)));

        $params = new OrgSlotUsageParam();
        $params
            ->setDateFrom('2014-01-01')
            ->setDateTo('2014-01-02')
            ->setSearchText('search_text');

        $this->dqlBuilder = new SlotUsageParamDqlBuilder($emMock, $params);
    }

    public function testGenerate()
    {
        $this->markTestSkipped();
        $result = $this->dqlBuilder->generate();

        $this->assertInstanceOf(\DvsaEntities\DqlBuilder\SlotUsageParamDqlBuilder::class, $result);

        $searchDql = "SELECT s, count(t.id) usage from DvsaEntities\\Entity\\Site s "
            . " INNER JOIN s.tests t  LEFT JOIN s.contacts sc  WITH sc.type = :contactType "
            . " INNER JOIN t.status ts  "
            . "LEFT JOIN sc.contactDetail cd  LEFT JOIN cd.address a "
            . " WHERE  (ts.name = '" . MotTestStatusName::PASSED . "') AND (t.testType IN (:SLOT_TEST_TYPES)) AND (s.organisation = :ORG_ID)"
            . " AND (t.completedDate >= :DATE_FROM) AND (t.completedDate <= :DATE_TO) AND "
            . "(s.siteNumber LIKE :SEARCH_TEXT OR
                 s.name liKE :SEARCH_TEXT OR
                 a.postcode LIKE :SEARCH_TEXT) GROUP BY s.id HAVING count(t.id) > 0 ORDER BY s.0 ASC";

        $this->assertEquals($searchDql, $this->dqlBuilder->getSearchDql());
    }
}
