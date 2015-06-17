<?php

namespace DvsaEntitiesTest\DqlBuilder;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaEntities\DqlBuilder\NativeQueryBuilder;

/**
 * Class NativeQueryBuilderTest
 *
 * @package DvsaEntitiesTest\DqlBuilder
 */
class NativeQueryBuilderTest extends AbstractServiceTestCase
{
    protected $queryBuilder;


    public function setup()
    {
        $this->queryBuilder = new NativeQueryBuilder;
    }

    /**
     * @dataProvider provideParts
     */
    public function testGetSql($parts)
    {
        foreach ($parts as $partType => $part) {
            switch ($partType) {
                case 'select':
                    foreach ($part as $aSelect) {
                        $this->queryBuilder->select($aSelect);
                    }
            break;
                case 'from':
                    foreach ($part as $aFrom) {
                        $this->queryBuilder->from($aFrom[0], $aFrom[1]);
                    }
            break;
                case 'join':
                    foreach ($part as $aJoin) {
                        $this->queryBuilder->join($aJoin[0], $aJoin[1], $aJoin[2]);
                    }
            break;
                case 'where':
                    foreach ($part as $aWhere) {
                        $this->queryBuilder->andWhere($aWhere);
                    }
            break;
                case 'param':
                    foreach ($part as $aParam) {
                        $this->queryBuilder->setParameter($aParam[0], $aParam[1]);
                    }
            break;
                case 'offset':
                    $this->queryBuilder->setOffset($part);
            break;
                case 'limit':
                    $this->queryBuilder->setLimit($part);
            break;
            }
        }
        $this->assertEquals($parts['expected'], $this->queryBuilder->getSql(), 'Sql does not match defined expected string');
    }

    public static function provideParts()
    {
        return
            [
                [
                    [
                        'select' =>
                            ['mt.number, ts.name AS status, mt.started_date',
                            'mt.registration, mt.vin, mt.make_code, mt.model_code',
                            's.site_number AS siteNumber, p.username as userName, tt.description as testTypeName',
                            'COALESCE(mt.completed_date, mt.started_date) AS testDate'
                        ],
                        'from' =>
                            [
                            ['mot_test', 'mt']
                            ],
                        'join' =>
                            [
                            ['site', 's', 's.id = mt.site_id'],
                            ['vehicle', 'v', 'v.id = mt.vehicle_id'],
                            ['make', 'vma', 'vma.code = mt.make_code'],
                            ['model', 'vmo', 'vmo.code = mt.model_code AND vmo.make_code = vma.code'],
                            ['mot_test_type', 'tt', 'tt.id = mt.mot_test_type_id'],
                            ['person', 'p', 'p.id = mt.person_id'],
                            ['mot_test_status', 'ts', 'ts.id = mt.status_id'],
                        ],
                        'where'  => [
                            'mt.number = :mtnumber',
                            'ts.name = :mtstatus',
                        ],
                        'param'  => [
                            ['mtnumber', 20],
                            ['mtstatus', 30],
                        ],
                        'offset' => 5,
                        'limit'  => 10,
                        'expected' => 'SELECT mt.number, ts.name AS status, mt.started_date, mt.registration, mt.vin, mt.make_code, mt.model_code, s.site_number AS siteNumber, p.username as userName, tt.description as testTypeName, COALESCE(mt.completed_date, mt.started_date) AS testDate FROM mot_test AS mt INNER JOIN site AS s ON s.id = mt.site_id INNER JOIN vehicle AS v ON v.id = mt.vehicle_id INNER JOIN make AS vma ON vma.code = mt.make_code INNER JOIN model AS vmo ON vmo.code = mt.model_code AND vmo.make_code = vma.code INNER JOIN mot_test_type AS tt ON tt.id = mt.mot_test_type_id INNER JOIN person AS p ON p.id = mt.person_id INNER JOIN mot_test_status AS ts ON ts.id = mt.status_id WHERE 1=1  AND mt.number = :mtnumber  AND ts.name = :mtstatus LIMIT 10 OFFSET 5'
                    ]
                ],
            ];
    }
}
