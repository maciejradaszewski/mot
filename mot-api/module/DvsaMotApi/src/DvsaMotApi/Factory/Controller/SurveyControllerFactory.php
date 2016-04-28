<?php

namespace DvsaMotApi\Factory\Controller;

use Aws\S3\S3Client;
use Doctrine\ORM\EntityManager;
use DvsaMotApi\Controller\SurveyController;
use DvsaMotApi\Service\SurveyService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class SurveyControllerFactory.
 */
class SurveyControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return SurveyController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /*
         * @var ServiceManager
         */
        $serviceLocator = $serviceLocator->getServiceLocator();

        $awsConfig = $serviceLocator->get('Config')['aws']['surveyReportsStorage'];

        $s3Client = new S3Client([
            'credentials' => [
                'key' => $awsConfig['accessKeyId'],
                'secret' => $awsConfig['secretKey'],
            ],
            'version' => 'latest',
            'region' => $awsConfig['region'],
        ]);

        $bucket = $awsConfig['bucket'];

        /*
         * @var SurveyService
         */
        $surveyService = new SurveyService(
            $serviceLocator->get(EntityManager::class),
            $s3Client,
            $bucket
        );

        return new SurveyController(
            $surveyService
        );
    }
}
