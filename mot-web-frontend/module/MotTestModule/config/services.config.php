<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */
use Dvsa\Mot\Frontend\MotTestModule\Factory\Service\SurveyServiceFactory;
use Dvsa\Mot\Frontend\MotTestModule\Factory\Validation\ContingencyTestValidatorFactory;
use Dvsa\Mot\Frontend\MotTestModule\Service\SurveyService;
use Dvsa\Mot\Frontend\MotTestModule\Validation\ContingencyTestValidator;

return [
    'factories' => [
        ContingencyTestValidator::class => ContingencyTestValidatorFactory::class,
        SurveyService::class => SurveyServiceFactory::class,
    ],
];
