<?php

namespace DvsaMotApiTest\Service\Generator;

/**
 * Class FixtureTemplate1615
 *
 * @package Generators
 */
class FixtureTemplate1615ResultFitnesse extends Fixturetemplate
{
    protected $counter = 0;

    public function getHeader()
    {
        return "!contents -R2 -g -p -f -h

NOTES:
- These fixtures are generated.. Do not edit them by hand
- Total Score is actually calculated on the server by adding up the passed RFRs
- The Total Score dispayed here is the expected calculated Value

'''In order to comply with acceptance criteria, I want the API to validate as agreed.'''

!|Vm 1615 Reinforcement Result Post Validation|
|Title|Test Number|Total Score|Case Outcome|Final Justification|Rfrs|Error Expected|Message Expected|Expected Failed Item|Found Error Expected?|Found Error Message Expected?|
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
        $values[] = $fixture['totalScore'];
        $values[] = $fixture['caseOutcome'];
        $values[] = $fixture['finalJustification'];
        $values[] = $this->buildRfrs($fixture['rfrs']);
        $values[] = $fixture['error'];
        $values[] = isset($fixture['message']) ? $fixture['message'] : null;
        $values[] = isset($fixture['failedItem']) ? $fixture['failedItem'] : null;
        $values[] = $fixture['error'] == 1 ? 'yes' : 'n/a';
        $values[] = $fixture['error'] == 1 ? 'yes' : 'n/a';
        return '|' . join('|', $values) . "|\n";
    }

    /**
     * @param $fixtureClassName
     * @param $fixtureName
     * @param $fixture
     *
     * @return null
     */
    public function getLinePost($fixtureClassName, $fixtureName, $fixture)
    {
        return null;
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return "\n\n";
    }

    /**
     * @param $rfrs
     *
     * @return null
     */
    protected function buildRfrs($rfrs)
    {
        if (is_array($rfrs)) {
            $results = array();
            foreach ($rfrs as $values) {
                $results[] .= sprintf(
                    "%s/%s/%s/%s/%s/%s",
                    $values['id'],
                    $values['score'],
                    $values['decision'],
                    $values['category'],
                    $values['justification'],
                    $values['error']
                );
            }
            return join(",", $results);
        }

        return null;
    }
}
