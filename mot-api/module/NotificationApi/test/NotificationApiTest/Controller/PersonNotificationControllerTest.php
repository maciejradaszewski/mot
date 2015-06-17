<?php

namespace NotificationApiTest\Controller;

use NotificationApi\Controller\PersonNotificationController;

/**
 * Class PersonNotificationControllerTest
 *
 * @package NotificationApiTest\Controller
 */
class PersonNotificationControllerTest extends AbstractNotificationApiControllerTestCase
{
    protected function setUp()
    {
        $this->controller = new PersonNotificationController();
        $this->setUpTestCase();
    }

    public function testWhiteList()
    {
        $this->assertMethodsOk(
            ['create', 'getList']
        );
    }
}
