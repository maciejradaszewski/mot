<?php

namespace DvsaMotApiTest\Service\Generator;

/**
 * Class FixtureTemplate
 *
 * @package Generators
 */
abstract class FixtureTemplate
{
    abstract public function getHeader();
    abstract public function getLinePre($fixtureClassName, $fixtureName, $fixture);
    abstract public function getLine($fixtureClassName, $fixtureName, $fixture);
    abstract public function getLinePost($fixtureClassName, $fixtureName, $fixture);
    abstract public function getFooter();
    //abstract public function getFilename();
}
