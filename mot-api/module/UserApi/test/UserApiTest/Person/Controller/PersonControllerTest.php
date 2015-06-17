<?php

namespace UserApiTest\Person\Controller;

use UserApi\Person\Controller\PersonController;

/**
 * Unit tests for PersonController
 */
class PersonControllerTest extends AbstractPersonControllerTestCase
{
    public function setUp()
    {
        $this->controller = new PersonController();
        $this->setUpTestCase();
    }

    public function testWhiteList()
    {
        $this->assertMethodsOk(
            ['get']
        );
    }
}
