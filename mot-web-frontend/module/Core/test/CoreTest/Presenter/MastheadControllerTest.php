<?php

namespace CoreTest\Presenter;

use Core\Presenter\MastheadPresenter;

class MastheadPresenterTest extends \PHPUnit_Framework_TestCase
{
    protected $presenter;

    protected $expectedMailtoUri = 'mailto:mot.modernisation@vosa.gsi.gov.uk?subject=MOT%20testing%20service%20feedback';

    protected function setUp()
    {
        $this->presenter = new MastheadPresenter;
    }

    protected function tearDown()
    {
        unset($this->presenter);
    }

    public function testGetFeedbackMailtoUri()
    {
        $this->assertEquals($this->presenter->getFeedbackMailtoUri(), $this->expectedMailtoUri);
    }
}
