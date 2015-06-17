<?php
namespace Dvsa\Mot\Frontend\Test;

use Dvsa\Mot\Frontend\Navigator;
use PHPUnit_Framework_TestCase;

class NavigatorTest extends PHPUnit_Framework_TestCase
{
    const ACTION_PREV_ACTION = 'prev-action';
    const ACTION_CURRENT_ACTION = 'current-action';
    const ACTION_NEXT_ACTION = 'next-action';

    private $steps
        = array(
            self::ACTION_PREV_ACTION    => 'Prev screen',
            self::ACTION_CURRENT_ACTION => 'Current screen',
            self::ACTION_NEXT_ACTION    => 'Next screen'
        );

    public function testGetNavigationLinks()
    {

        //given
        $navigator = new Navigator($this->steps);

        //when
        $navigationLinks = $navigator->getNavigationLinks(self::ACTION_CURRENT_ACTION);

        //then
        $this->assertEquals($this->steps[self::ACTION_PREV_ACTION], $navigationLinks['prev']['label']);
        $this->assertEquals(self::ACTION_PREV_ACTION, $navigationLinks['prev']['step']);

        $this->assertEquals($this->steps[self::ACTION_NEXT_ACTION], $navigationLinks['next']['label']);
        $this->assertEquals(self::ACTION_NEXT_ACTION, $navigationLinks['next']['step']);
        $this->assertEquals(Navigator::PARAM_NEXT_STEP, $navigationLinks['next']['buttonName']);
    }
}

?>