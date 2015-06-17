<?php

namespace DvsaElasticSearchTest\Query;

use Doctrine\ORM\EntityManager;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\XMock;

use DvsaElasticSearch\Query\FbQuerySite;
use DvsaElasticSearch\Query\FbQueryVehicle;
use DvsaElasticSearch\Query\SuperSearchQuery;
use DvsaElasticSearchTest\EsHelperTest;
use DvsaEntities\DqlBuilder\SearchParam\VehicleSearchParam;
use DvsaEntities\DqlBuilder\SearchParam\VehicleTestingStationSearchParam;
use DvsaEntities\Entity\Make;
use DvsaEntities\Entity\Model;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\Vehicle;
use DvsaEntities\Repository\VehicleRepository;
use \PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\DateTime;

/**
 * Class SuperSearchQueryTest
 *
 * @package DvsaElasticSearchTest\Query
 */
class SuperSearchQueryTest extends \PHPUnit_Framework_TestCase
{
    /** @var  ServiceManager */
    protected $serviceManager;

    /** @var  SuperSearchQuery */
    protected $SuperQuery;

    protected $mockEm;
    protected $mockEsConn;
    protected $mockSite;
    protected $mockVehicle;

    public function setup()
    {
        $this->SuperQuery = new SuperSearchQuery();
        $this->serviceManager = Bootstrap::getServiceManager();

        $this->mockEm = XMock::of(EntityManager::class, ['getRepository']);
        $this->mockSite = XMock::of(Site::class, ['getTypes', 'getStatuses', 'getBySiteNumber']);
        $this->mockVehicle = XMock::of(VehicleRepository::class, ['search']);
    }

    public function testSuperSearchQueryExecuteVehicleFb()
    {

        $this->mockEm->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($this->mockVehicle));

        $this->mockVehicle->expects($this->once())
            ->method('search')
            ->will($this->returnValue($this->getVehicleEntity()));

        $this->SuperQuery->execute($this->getSearchParamVehicle(),  new FbQueryVehicle());
    }

    protected function getSearchParamVehicle()
    {
        $request = new VehicleSearchParam($this->mockEm, 'GGG455', 'registration');
        $request->setFormat('DATA_TABLES');
        $request->process();

        return $request;
    }

    protected function getVehicleQuery()
    {
        $query = EsHelperTest::getDefaultQuery();
        $query['query']['bool']['must'][0]['query_string'] = [
            'default_field' => 'registration.lower_case_sort',
            'query'         => 'GGG455',
        ];
        $query['sort'] = [[
            'registration.lower_case_sort' => 'asc'
        ]];
        return $query;
    }

    protected function getEsQueryVehicleReturn()
    {
        $result = EsHelperTest::getDefaultResult();
        $result['hits']['hits'][] = [
            '_index'  => 'development_vehicle',
            '_type'   => 'vehicle',
            '_id'     => '14',
            '_score'  => null,
            '_source' => [
                'id' => 26,
                'vin' => '1HD1BDK10DY123456',
                'registration' => 'SSE24MAR',
                'make' => 'Harley Davidson',
                'model' => 'Service Car Trike',
                'displayDate' => '2014-10-07 12:32:45',
                'updatedDate_display' => '07 Oct 2014 12:32',
                'updatedDate_timestamp' => 1412676600,
            ]
        ];
        return $result;
    }

    protected function getVehicleEntity()
    {
        $make = new Make();
        $make->setName('Ferrari');

        $model = new Model();
        $model->setName('Enzo');
        $model->setMake($make);

        $vehicle = new Vehicle();
        $vehicle->setId('1');
        $vehicle->setRegistration('GGG455');
        $vehicle->setVin('LKVY64VG5KNV87');
        $vehicle->setModel($model);

        $results[] = $vehicle;
        return $results;
    }
}
