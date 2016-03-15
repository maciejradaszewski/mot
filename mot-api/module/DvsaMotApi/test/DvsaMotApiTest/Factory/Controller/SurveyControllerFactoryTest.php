<?php

namespace DvsaMotApiTest\Factory\Controller;


use Doctrine\ORM\EntityManager;
use DvsaCommonTest\TestUtils\ServiceFactoryTestHelper;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotApi\Controller\SurveyController;
use DvsaMotApi\Factory\Controller\SurveyControllerFactory;
use DvsaMotApi\Service\SurveyService;

class SurveyControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryCreatesInstance()
    {
        ServiceFactoryTestHelper::testCreateServiceForCM(
            SurveyControllerFactory::class,
            SurveyController::class, [
                SurveyService::class => SurveyService::class,
                EntityManager::class => EntityManager::class,
            ]
        );
    }
}