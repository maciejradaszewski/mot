<?php

namespace UserApiTest\Person\Controller;

use UserApi\Person\Controller\MotTestingAuthorisationController;

/**
 * Unit tests for MotTestingAuthorisationController
 */
class MotTestingAuthorisationControllerTest extends AbstractPersonControllerTestCase
{
    public function setUp()
    {
        $this->controller = new MotTestingAuthorisationController();
        $this->setUpTestCase();
    }

    public function testWhiteList()
    {
        $this->assertMethodsOk(
            ['get', 'update']
        );
    }
}
