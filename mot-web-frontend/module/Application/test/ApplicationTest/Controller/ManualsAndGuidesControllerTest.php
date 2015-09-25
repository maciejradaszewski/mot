<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace ApplicationTest\Controller;

use Application\Controller\ManualsAndGuidesController;
use CoreTest\Controller\AbstractLightWebControllerTest;

/**
 * Covers ManualsAndGuidesController.
 */
class ManualsAndGuidesControllerTest extends AbstractLightWebControllerTest
{
    public function testWithEmptyConfig()
    {
        $this->setController(new ManualsAndGuidesController([]));
    }

    public function testWithFullConfig()
    {
        $this->setController(new ManualsAndGuidesController($this->getFullConfig()));

        $resourceLinks = $this->getController()->indexAction();
    }

    /**
     * @return array
     */
    private function getFullConfig()
    {
        return [
            [
                'name'      => 'MOT inspection manual for class 1 and 2 vehicles',
                'url'       => '/documents/manuals/m1i00000001.htm',
                'help_text' => 'Manual for motor bicycle and side car testing',
            ],
            [
                'name'      => 'MOT inspection manual for class 3, 4, 5, and 7 vehicles',
                'url'       => '/documents/manuals/m4i00000001.htm',
                'help_text' => 'Manual for testing private passenger and light commercial vehicles',
            ],
            [
                'name'      => 'MOT testing guide',
                'url'       => '/documents/manuals/tgi00000001.htm',
                'help_text' => 'Guidance on how the MOT scheme is run',
            ],
            [
                'name'      => 'In service exhaust emission standards for road vehicles: 18th edition',
                'url'       => 'https://www.gov.uk/government/uploads/system/uploads/attachment_data/file/348035/18th-edition-emissions-book-complete.pdf',
                'help_text' => 'Standards for checking vehicle exhaust emission procedures and limits',
            ],
        ];
    }
}
