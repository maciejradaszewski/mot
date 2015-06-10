<?php

namespace UserAdminTest\Factory\Service;

use CoreTest\Service\StubCatalogService;
use DvsaClient\Mapper\TesterQualificationStatusMapper;
use DvsaClient\MapperFactory;
use DvsaCommon\HttpRestJson\Client;
use DvsaCommonTest\TestUtils\XMock;
use UserAdmin\Service\TesterQualificationStatusService;
use UserAdmin\Factory\Service\TesterQualificationStatusServiceFactory;
use Zend\ServiceManager\ServiceManager;

class TesterQualificationStatusServiceFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateService()
    {
        $serviceManager = new ServiceManager();

        $testerQualificationStatusMapperMock = XMock::of(TesterQualificationStatusMapper::class);
        $mapperFactoryMock = XMock::of(MapperFactory::class);
        $mapperFactoryMock
            ->expects($this->any())
            ->method('__get')
            ->will($this->returnValue($testerQualificationStatusMapperMock));
        $serviceManager->setService(MapperFactory::class, $mapperFactoryMock);

        $restClientMock = XMock::of(Client::class);
        $serviceManager->setService(Client::class, $restClientMock);

        $catalogService = new StubCatalogService();
        $serviceManager->setService('CatalogService', $catalogService);

        $factory = new TesterQualificationStatusServiceFactory();

        $this->assertInstanceOf(
            TesterQualificationStatusService::class,
            $factory->createService($serviceManager)
        );
    }
}
