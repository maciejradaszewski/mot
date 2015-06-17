<?php

namespace DvsaMotApiTest\Service\Generator;

/**
 * Class FixtureStory
 *
 * @package Generators
 */
abstract class FixtureStory
{
    protected $fixtureTemplate = null;

    /**
     * Returns an array of fixture classes to get the fixtures from
     * - will call {$classname}->getFixtures() on each class
     *   and merge the fixtures into one big array.. stored per getFixtureName()
     *
     * array(
     *      getFixtureName() -> getFixtures()
     *      getFixtureName() -> getFixtures()
     * )
     *
     * @return array()
     */
    abstract public function getFixtureClassNames();

    abstract public function getFitnesseFilePath();

    abstract public function processFixtures();

    public function render()
    {
        if (!($this->fixtureTemplate instanceof FixtureTemplate)) {
            throw new \Exception("No fixture template assigned");
        }

        $result = $this->fixtureTemplate->getHeader();

        if (is_array($this->fixtures)) {
            foreach ($this->fixtures as $fixtureClass => $fixtureValues) {
                foreach ($fixtureValues['fixtures'] as $fixture) {
                    if (isset($fixture['fitnesse']) && intval($fixture['fitnesse']) === 0) {
                        echo "skipping test {$fixture['id']}\n";
                        continue;
                    }
                    $result .= $this->fixtureTemplate->getLinePre($fixtureClass, $fixtureValues['name'], $fixture);
                    $result .= $this->fixtureTemplate->getLine($fixtureClass, $fixtureValues['name'], $fixture);
                    $result .= $this->fixtureTemplate->getLinePost($fixtureClass, $fixtureValues['name'], $fixture);
                }
            }
        }

        $result .= $this->fixtureTemplate->getFooter();
        return $result;
    }

    protected $fixtures = array();

    public function gatherFixtures()
    {
        $classNames = $this->getFixtureClassNames();

        $this->fixtures = array();

        foreach ($classNames as $className => $filepath) {
            $fullpath = '../' . $filepath;
            //echo "requiring ".$fullpath."\n";
            require_once($fullpath);
            $object = new $className;
            $this->fixtures[$className] = [
                'name' => $object->getFixtureName(),
                'fixtures' => $object->getFixtures()
            ];
        }

        // let the story process (fix) the fixtures
        $this->processFixtures();
    }

    /**
     * @param null $fixtureTemplate
     *
     * @return FixtureStory
     */
    public function setFixtureTemplate($fixtureTemplate)
    {
        $this->fixtureTemplate = $fixtureTemplate;
        return $this;
    }

    /**
     * @return null
     */
    public function getFixtureTemplate()
    {
        return $this->fixtureTemplate;
    }
}
