<?php

namespace DvsaMotApiTest\Service\Generator;

/**
 * Class FixtureTemplate1615
 *
 * @package Generators
 */
class FixtureTemplate1615Fitnesse extends Fixturetemplate
{
    protected $counter = 0;

    public function getHeader()
    {
        return "!contents -R2 -g -p -f -h

NOTE: These fixtures are generated.. Do not edit them by hand

'''In order to comply with acceptance criteria, I want the API to validate as agreed.'''

!|Vm 1615 Reinforcement Post Validation|
|Title|Test Number|Id|Score|Decision|Category|Justification|Error Expected|Message Expected|Expected Failed Item|Found Error Expected?|Found Error Message Expected?|Notes|
";
    }
    public function getLinePre($fixtureClassName, $fixtureName, $fixture)
    {
        return null;
    }

    /**
     * @param $fixtureClassName
     * @param $fixtureName
     * @param $fixture
     *
     * @return string
     */
    public function getLine($fixtureClassName, $fixtureName, $fixture)
    {
        $this->counter++;
        if ($this->counter <= 40) {
            //return null;
        }
        $values = [$fixtureName];
        $values[] = $fixture['testNumber'] = $this->counter;
        $values[] = $fixture['id'];
        $values[] = $fixture['score'];
        $values[] = $fixture['decision'];
        $values[] = $fixture['category'];
        $values[] = $fixture['justification'];
        $values[] = $fixture['error'];
        $values[] = $fixture['message'];
        $values[] = isset($fixture['failedItem']) ? $fixture['failedItem'] : null;
        $values[] = $fixture['error'] == 1 ? 'yes' : 'n/a';
        $values[] = $fixture['error'] == 1 ? 'yes' : 'n/a';
        $values[] = isset($fixture['notes']) ? $fixture['notes'] : null;
        return '|' . join('|', $values) . "|\n";
    }

    public function getLinePost($fixtureClassName, $fixtureName, $fixture)
    {
        return null;
    }
    public function getFooter()
    {
        return "\n\n";
    }
}
