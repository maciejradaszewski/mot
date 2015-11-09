<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaMotApiTest\Factory\Controller;

use DvsaCommonTest\TestUtils\ServiceFactoryTestHelper;
use DvsaMotApi\Controller\ContingencyTestController;
use DvsaMotApi\Factory\Controller\ContingencyTestControllerFactory;
use DvsaMotApi\Service\EmergencyService;
use SiteApi\Service\SiteService;

class ContingencyTestControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryCreatesInstance()
    {
        ServiceFactoryTestHelper::testCreateServiceForCM(
            ContingencyTestControllerFactory::class,
            ContingencyTestController::class, [
                EmergencyService::class         => EmergencyService::class,
                SiteService::class              => SiteService::class,
            ]
        );
    }
}
