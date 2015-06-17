<?php

namespace SiteApiTest\Controller;

use SiteApi\Controller\SiteTestingDailyScheduleController;

/**
 * Class SiteTestingDailyScheduleControllerTest
 *
 * @package SiteApiTest\Controller
 */
class SiteTestingDailyScheduleControllerTest extends AbstractSiteApiControllerTest
{
    public function setUp()
    {
        $this->controller = new  SiteTestingDailyScheduleController();
        $this->setUpTestCase();
    }

    public function testWhiteList()
    {
        $this->assertMethodsOk(
            ['get', 'update']
        );
    }
}
