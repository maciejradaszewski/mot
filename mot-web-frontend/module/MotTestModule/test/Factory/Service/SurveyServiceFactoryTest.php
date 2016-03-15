<?php

namespace DvsaMotTestTest\Factory\Service;


use Dvsa\Mot\Frontend\MotTestModule\Factory\Service\SurveyServiceFactory;
use Dvsa\Mot\Frontend\MotTestModule\Service\SurveyService;
use DvsaCommon\HttpRestJson\Client;
use DvsaCommonTest\TestUtils\ServiceFactoryTestHelper;


class SurveryServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryCreatesInstance()
    {
        ServiceFactoryTestHelper::testCreateServiceForSM(
            SurveyServiceFactory::class,
            SurveyService::class, [
                Client::class => Client::class
            ]
        );
    }
}