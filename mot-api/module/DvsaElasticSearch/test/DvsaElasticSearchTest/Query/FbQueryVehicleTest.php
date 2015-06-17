<?php

namespace DvsaElasticSearchTest\Query;

use DvsaEntities\Entity\Make;
use DvsaEntities\Entity\Model;
use DvsaEntities\Entity\Vehicle;
use DvsaCommonTest\TestUtils\XMock;
use DvsaElasticSearch\Query\FbQueryVehicle;
use DvsaEntities\DqlBuilder\SearchParam\VehicleSearchParam;
use DvsaEntities\Repository\VehicleRepository;
use \PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\DateTime;

/**
 * Class FbQueryVehicleTest
 *
 * @package DvsaElasticSearchTest\Query
 */
class FbQueryVehicleTest extends \PHPUnit_Framework_TestCase
{
    /** @var  ServiceManager */
    protected $serviceManager;

    /** @var  FbQueryVehicle */
    protected $FbQueryVehicle;

    protected $mockSearchParam;
    protected $mockRepository;

    public function setup()
    {
        $this->FbQueryVehicle = new FbQueryVehicle();
        $this->mockRepository = XMock::of(VehicleRepository::class, ['search']);

        $this->mockSearchParam =  $this->getMock(
            VehicleSearchParam::class,
            ['toArray', 'getRepository'], [], '', false
        );
    }

    public function testFbQueryMotTestExecute()
    {
        $this->mockSearchParam->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue($this->getVehicleSearchParams()));

        $this->mockSearchParam->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($this->mockRepository));

        $this->mockRepository->expects($this->once())
            ->method('search')
            ->will($this->returnValue($this->getVehicleEntity()));

        $this->assertEquals($this->getResultFb(), $this->FbQueryVehicle->execute($this->mockSearchParam));
    }

    protected function getVehicleSearchParams()
    {
        return [
            'search' => 'GGG455',
            'type'   => 'registration',
        ];
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
        $vehicle->setMake($make);

        $results[] = $vehicle;
        return $results;
    }

    protected function getResultFb()
    {
        return [
            'searched' => [
                'search' => 'GGG455',
                'isElasticSearch' => false,
                'type' => 'registration',
            ],
            'resultCount' => 1,
            'totalResultCount' => 1,
            'data' => [
                '1' => [
                    'vin' => 'LKVY64VG5KNV87',
                    'registration' => 'GGG455',
                    'model' => 'Enzo',
                    'make' => 'Ferrari',
                    'displayDate' => null,
                ]
            ],
        ];
    }
}
