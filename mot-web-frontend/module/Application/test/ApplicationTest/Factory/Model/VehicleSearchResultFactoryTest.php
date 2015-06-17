<?php

namespace DvsaMotTestTest\Factory\Controller;

use Application\Service\CatalogService;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotTest\Model\VehicleSearchResult;
use DvsaMotTest\Factory\Model\VehicleSearchResultFactory;

/**
 * Class VehicleSearchResultFactoryTest.
 *
 * @covers \DvsaMotTest\Controller\SpecialNoticesController
 */
class VehicleSearchResultFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testVehicleSearchResultModelFactory()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);

        $serviceManager->setService(ParamObfuscator::class, XMock::of(ParamObfuscator::class));

        $factory = (new VehicleSearchResultFactory())->createService($serviceManager);

        $this->assertInstanceOf(VehicleSearchResult::class, $factory);
    }
}
