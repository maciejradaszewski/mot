<?php

namespace DvsaMotApiTest\Service\Generator;

//require_once './FixtureStory.php';
//use Generators\FixtureStory;
use DvsaMotApi\Service\RfrValidator\BaseValidator;

/**
 * Class FixtureStory1615
 *
 * @package Generators
 */
class FixtureStory1615 extends FixtureStory
{
    /**
     * @return array
     */
    public function getFixtureClassNames()
    {
        return [
            'DvsaMotApiTest\Service\RfrValidator\CheckCategoryAllowedForDefectNotApplicableTest' =>
                'mot/mot-api/module/DvsaMotApi/test/DvsaMotApiTest/Service/RfrValidator/CheckCategoryAllowedForDefectNotApplicableTest.php',
            'DvsaMotApiTest\Service\RfrValidator\CheckCategoryPleaseSelectForDefectTest' =>
                'mot/mot-api/module/DvsaMotApi/test/DvsaMotApiTest/Service/RfrValidator/CheckCategoryPleaseSelectForDefectTest.php',
            'DvsaMotApiTest\Service\RfrValidator\CheckDecisionsForCategoryNotApplicableTest' =>
                'mot/mot-api/module/DvsaMotApi/test/DvsaMotApiTest/Service/RfrValidator/CheckDecisionsForCategoryNotApplicableTest.php',
            'DvsaMotApiTest\Service\RfrValidator\CheckJustificationForScoreDisregardTest' =>
                'mot/mot-api/module/DvsaMotApi/test/DvsaMotApiTest/Service/RfrValidator/CheckJustificationForScoreDisregardTest.php',
            'DvsaMotApiTest\Service\RfrValidator\CheckScoreForDefectNotApplicableTest' =>
                'mot/mot-api/module/DvsaMotApi/test/DvsaMotApiTest/Service/RfrValidator/CheckScoreForDefectNotApplicableTest.php',
        ];
    }

    public function getFitnesseFilePath()
    {
        return '../mot/mot-fitnesse/FitNesseRoot/FrontPage/MotTesting/UserStoryVm1615/content.txt';
    }

    public function processFixtures()
    {
        /**
         * $this->fixtures[$className] = [
         *  'name' => $object->getFixtureName(),
         *  'fixtures' => $object->getFixtures()
         * ];
         */
        foreach ($this->fixtures as $class => $values) {
            $className   = array_pop(explode('\\', $class));
            foreach ($values['fixtures'] as $id => $fixture) {
                // fix category issues
                if ($fixture['category'] == 0) {
                    if ($fixture['decision'] == 2 || $fixture['decision'] == 3) {
                        $values['fixtures'][$id]['message'] = BaseValidator::INVALID_CATEGORY_FOR_DEFECT;
                        $values['fixtures'][$id]['error'] = 1;
                        $values['fixtures'][$id]['category'] = ''; // marks that this was a service error
                        $values['fixtures'][$id]['notes'] = 'forced in processing';
                    } else {
                        $values['fixtures'][$id]['message'] = 'Category not found';
                        $values['fixtures'][$id]['error'] = 1;
                        $values['fixtures'][$id]['failedItem'] = ''; // marks that this was a service error
                        $values['fixtures'][$id]['notes'] = 'forced in processing';
                    }
                }
                // fix defect issues
                if ($fixture['decision'] == 0) {
                    if ($className == 'CheckJustificationForScoreDisregardTest') {
                        $values['fixtures'][$id]['message'] = 'Decision not found';
                    } else {
                        $values['fixtures'][$id]['message'] = 'Decision not found';
                    }

                    $values['fixtures'][$id]['error'] = 1;
                    $values['fixtures'][$id]['failedItem'] = ''; // marks that this was a service error
                    $values['fixtures'][$id]['notes'] = 'forced in processing';
                }
                if ($className == 'CheckJustificationForScoreDisregardTest'&& $fixture['id'] == 1) {
                    $values['fixtures'][$id]['justification'] = 'some value';
                    $values['fixtures'][$id]['notes'] = 'forced in processing';
                }
                if ($className == 'CheckScoreForDefectNotApplicableTest'&& $fixture['decision'] == 1) {
                    $values['fixtures'][$id]['message'] = BaseValidator::INVALID_CATEGORY_FOR_DEFECT;
                    $values['fixtures'][$id]['error'] = 1;
                    $values['fixtures'][$id]['failedItem'] = 'category'; // marks that this was a service error
                    $values['fixtures'][$id]['notes'] = 'forced in processing';
                }
            }

            $this->fixtures[$class]= $values;
        }
    }
}
