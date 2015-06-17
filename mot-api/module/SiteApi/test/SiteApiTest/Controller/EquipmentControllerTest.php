<?php

namespace SiteApiTest\Controller;

use SiteApi\Controller\EquipmentController;

/**
 * Class EquipmentControllerTest
 *
 * @package SiteApiTest\Controller
 */
class EquipmentControllerTest extends AbstractSiteApiControllerTest
{
    public function setUp()
    {
        $this->controller = new  EquipmentController();
        $this->setUpTestCase();
    }

    public function testWhiteList()
    {
        $this->assertMethodsOk(
            ['get']
        );
    }
}
