<?php

namespace DvsaMotApiTest\Service\Generator;

use DvsaMotApi\Service\RfrValidator\BaseResultValidator;

/**
 * Class FixtureStory1615
 *
 * @package Generators
 */
class FixtureStory1615Result extends FixtureStory
{
    /**
     * @return array
     */
    public function getFixtureClassNames()
    {
        return [
            'DvsaMotApiTest\Service\RfrValidator\CheckAdvisoryWarningHasJustificationAgainstScoreTest' =>
                'mot/mot-api/module/DvsaMotApi/test/DvsaMotApiTest/Service/RfrValidator/CheckAdvisoryWarningHasJustificationAgainstScoreTest.php',
            'DvsaMotApiTest\Service\RfrValidator\CheckDisciplinaryActionHasJustificationAgainstScoreTest' =>
                'mot/mot-api/module/DvsaMotApi/test/DvsaMotApiTest/Service/RfrValidator/CheckDisciplinaryActionHasJustificationAgainstScoreTest.php',
            'DvsaMotApiTest\Service\RfrValidator\CheckNoFurtherActionHasJustificationAgainstScoreTest' =>
                'mot/mot-api/module/DvsaMotApi/test/DvsaMotApiTest/Service/RfrValidator/CheckNoFurtherActionHasJustificationAgainstScoreTest.php'
        ];
    }

    public function getFitnesseFilePath()
    {
        return '../mot/mot-fitnesse/FitNesseRoot/FrontPage/MotTesting/UserStoryVm1615Result/content.txt';
    }

    public function processFixtures()
    {
        /**
         * $this->fixtures[$className] = [
         *  'name' => $object->getFixtureName(),
         *  'fixtures' => $object->getFixtures()
         * ];
         */
        /*
        foreach ($this->fixtures as $class => $values) {
            //$className   = array_pop(explode('\\', $class));

            //$this->fixtures[$class]= $values;
        }
        */
    }
}
