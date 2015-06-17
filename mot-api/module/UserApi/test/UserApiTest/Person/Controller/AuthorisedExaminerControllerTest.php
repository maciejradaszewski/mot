<?php

namespace UserApiTest\Person\Controller;

use UserApi\Person\Controller\AuthorisedExaminerController;

/**
 * Unit tests for AuthorisedExaminerController
 */
class AuthorisedExaminerControllerTest extends AbstractPersonControllerTestCase
{
    public function setUp()
    {
        $this->controller = new AuthorisedExaminerController();
        $this->setUpTestCase();
    }

    public function testWhiteList()
    {
        $this->assertMethodsOk(
            ['get']
        );
    }
}
